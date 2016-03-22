package com.hess.hessandroid;

import android.app.Activity;
import android.app.DialogFragment;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.TimePicker;

import com.hess.hessandroid.dialogs.TimePickerFragment;
import com.hess.hessandroid.enums.PeakType;
import com.hess.hessandroid.enums.WeekType;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;

public class SetScheduleActivity extends Activity implements TimePickerDialog.OnTimeSetListener {
    private final static String LOG_STRING = "HESS_SetSchedule";
    private static String PICKER_TYPE_START = "START";
    private static String PICKER_TYPE_END = "END";


    private String mCurrentPickerType;
    private boolean isNewSchedule = true;
    private HessSchedule mSchedule;
    private Spinner spinWeekType;
    private Spinner spinPeakType;
    private TextView tvStartTime;
    private TextView tvEndTime;
    private TextView tvErrorMsg;
    private Button btnSetSchedule;
    private ArrayList<HessSchedule> mList;
    private int skipIndexForSchedule = -1;

    private int pickerStartHour = 1;
    private int pickerStartMin = 50;
    private int pickerEndHour = 2;
    private int pickerEndMin = 51;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_set_schedule);
        Bundle b = getIntent().getExtras();

        isNewSchedule = b.getBoolean("IsNew");
        mList = (ArrayList<HessSchedule>)getIntent().getExtras().get("SCHEDULELIST");

        spinWeekType = (Spinner)findViewById(R.id.spinnerWeekType);
        spinPeakType = (Spinner)findViewById(R.id.spinnerPeakType);

        spinPeakType.setAdapter(new ArrayAdapter<PeakType>(this, R.layout.spinner_item, PeakType.values()));
        spinWeekType.setAdapter(new ArrayAdapter<WeekType>(this, R.layout.spinner_item, WeekType.values()));

        tvStartTime = (TextView) findViewById(R.id.tvStartTime);
        tvEndTime = (TextView) findViewById(R.id.tvEndTime);
        tvErrorMsg = (TextView) findViewById(R.id.tvErrorMsg);
        tvErrorMsg.setVisibility(View.INVISIBLE);

        btnSetSchedule = (Button) findViewById(R.id.btnSetSchedule);
        btnSetSchedule.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                closeActivityWithSuccess();
            }
        });

        android.app.ActionBar ab = getActionBar();

        // Update
        if(!isNewSchedule) {
            mSchedule = (HessSchedule)getIntent().getExtras().get("SCHEDULE");
            spinWeekType.setSelection(mSchedule.WeekTypeID-1);
            spinPeakType.setSelection(mSchedule.PeakTypeID - 1);
            pickerStartHour = Integer.parseInt(mSchedule.StartTime.split(":")[0]);
            pickerStartMin = Integer.parseInt(mSchedule.StartTime.split(":")[1]);
            pickerEndHour = Integer.parseInt(mSchedule.EndTime.split(":")[0]);
            pickerEndMin = Integer.parseInt(mSchedule.EndTime.split(":")[1]);

            tvStartTime.setText(mSchedule.getStartTimeAMPM());
            tvEndTime.setText(mSchedule.getEndTimeAMPM());
            ab.setTitle(R.string.title_activity_set_schedule_update);
            btnSetSchedule.setText("UPDATE SCHEDULE");
        } else {
            mSchedule = new HessSchedule();
            final Calendar c = Calendar.getInstance();
            pickerStartHour = c.get(Calendar.HOUR_OF_DAY);
            pickerEndHour = c.get(Calendar.HOUR_OF_DAY);
            pickerStartMin = c.get(Calendar.MINUTE);
            pickerEndMin = c.get(Calendar.MINUTE) + 1;
            btnSetSchedule.setText("CREATE SCHEDULE");
            ab.setTitle(R.string.title_activity_set_schedule_new);
        }
    }

    public void showStartTimePickerDialog(View v) {
        mCurrentPickerType = PICKER_TYPE_START;
        TimePickerFragment newFragment = new TimePickerFragment();
        newFragment.setTime(pickerStartHour, pickerStartMin);
        newFragment.show(getFragmentManager(), "timePicker");
    }

    public void showEndTimePickerDialog(View v) {
        mCurrentPickerType = PICKER_TYPE_END;
        TimePickerFragment  newFragment = new TimePickerFragment();
        newFragment.setTime(pickerEndHour, pickerEndMin);
        newFragment.show(getFragmentManager(), "timePicker");
    }

    private String convertToTimeToMySQLFormat(int hour, int min) {
        String result = "";
        if (hour < 10)
            result += "0";
        result += hour + ":";

        if (min < 10)
            result += "0";
        result += min;
        result += ":00";

        return result;
    }

    @Override
    public void onTimeSet(TimePicker view, int hour, int minute) {
        // Clear error message
        setErrorMessage(null);
        if (mCurrentPickerType == PICKER_TYPE_START) {
            pickerStartHour = hour;
            pickerStartMin = minute;
            mSchedule.StartTime = convertToTimeToMySQLFormat(hour, minute);
            tvStartTime.setText(mSchedule.getStartTimeAMPM());
        } else if (mCurrentPickerType == PICKER_TYPE_END) {
            pickerEndHour = hour;
            pickerEndMin = minute;
            mSchedule.EndTime = convertToTimeToMySQLFormat(hour, minute);
            tvEndTime.setText(mSchedule.getEndTimeAMPM());
        } else {
            Log.e(LOG_STRING, "Error: Invalid listener type for time set.");
        }
    }

    private boolean isValidSchedule() {
        if(pickerStartHour >= pickerEndHour
            && pickerStartMin >= pickerEndMin) {
            setErrorMessage("ERROR: the start time must be before the end time.");
            return false;
        } else {
            setErrorMessage(null);
            return true;
        }
    }

    private boolean isScheduleConflicting() {
        Date newStart;
        Date newEnd;

        try {
            newStart = HessSchedule.inputDateFormat.parse(convertToTimeToMySQLFormat(pickerStartHour, pickerStartMin));
            newEnd = HessSchedule.inputDateFormat.parse(convertToTimeToMySQLFormat(pickerEndHour, pickerEndMin));
        } catch (Exception e) {
            return false;
        }

        int index = 0;
        for(HessSchedule sch : mList) {
            if(index == skipIndexForSchedule)
                continue;

            Date currStart = sch.getStartTimeInDateFormat();
            Date currEnd = sch.getEndTimeInDateFormat();

            if((newStart.before(currEnd) && newStart.after(currStart))
                    || (newEnd.before(currEnd) && newEnd.after(currStart))){
                setScheduleConflict(index);
                return true;
            }

            index++;
        }
        return false;
    }

    private void setScheduleConflict(int index) {
        String s = "SCHEDULE CONFLIT: the current schedule conflicts with the schedule (" + mList.get(index).getStartTimeAMPM() + " - " + mList.get(index).getEndTimeAMPM() + ").";
        setErrorMessage(s);
    }

    private void setErrorMessage(String err) {
        if(err != null) {
            tvErrorMsg.setVisibility(View.VISIBLE);
            tvErrorMsg.setText(err);
        } else {
            tvErrorMsg.setVisibility(View.INVISIBLE);
        }
    }

    public void closeActivityWithSuccess() {
        if(isValidSchedule() && !isScheduleConflicting()) {

            HessSchedule schedule = new HessSchedule();
            if(!isNewSchedule)
                schedule.PeakScheduleID = mSchedule.PeakScheduleID;
            schedule.EndTime = convertToTimeToMySQLFormat(pickerEndHour, pickerEndMin);
            schedule.StartTime = convertToTimeToMySQLFormat(pickerStartHour, pickerStartMin);
            PeakType peak = (PeakType)spinPeakType.getSelectedItem();
            WeekType week = (WeekType)spinWeekType.getSelectedItem();
            schedule.PeakTypeID = peak.getID();
            schedule.WeekTypeID = week.getID();

            Intent returnIntent = new Intent();
            returnIntent.putExtra("SCHEDULE", schedule);
            setResult(Activity.RESULT_OK,returnIntent);
            finish();
        }
    }
}
