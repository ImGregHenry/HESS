package com.hess.hessandroid;

import android.app.DialogFragment;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.TimePicker;

import com.hess.hessandroid.dialogs.TimePickerFragment;
import com.hess.hessandroid.enums.PeakType;
import com.hess.hessandroid.enums.WeekType;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.volley.VolleyRequest;

import org.json.JSONArray;
import org.json.JSONObject;

public class ScheduleActivity extends AppCompatActivity implements TimePickerDialog.OnTimeSetListener {
    private final static String LOG_STRING = "HESS_Schedule";
    private static String PICKER_TYPE_START = "START";
    private static String PICKER_TYPE_END = "END";



    int pickerStartHour = 1;
    int pickerStartMin = 50;
    int pickerEndHour = 2;
    int pickerEndMin = 51;

    Spinner peakSpinner;
    Spinner weekSpinner;
    TextView tvStartTime;
    TextView tvEndTime;
    private String mCurrentPickerType;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_schedule);

        tvStartTime = (TextView) findViewById(R.id.tvStartTime);
        tvEndTime = (TextView) findViewById(R.id.tvEndTime);

        tvStartTime.setText(convertToTimeToMySQLFormat(pickerStartHour, pickerStartMin));
        tvEndTime.setText(convertToTimeToMySQLFormat(pickerEndHour, pickerEndMin));

        peakSpinner = (Spinner) findViewById(R.id.spinnerPeakType);
        peakSpinner.setAdapter(new ArrayAdapter<PeakType>(this, android.R.layout.simple_list_item_1, PeakType.values()));

        weekSpinner = (Spinner) findViewById(R.id.spinnerWeekType);
        weekSpinner.setAdapter(new ArrayAdapter<WeekType>(this, android.R.layout.simple_list_item_1, WeekType.values()));

        // Request data from cloud server
        VolleyRequest req = new VolleyRequest();
        req.getSchedulerData(this);
    }

    public void showStartTimePickerDialog(View v) {
        mCurrentPickerType = PICKER_TYPE_START;
        DialogFragment newFragment = new TimePickerFragment();
        newFragment.show(getFragmentManager(), "timePicker");
    }

    public void showEndTimePickerDialog(View v) {
        mCurrentPickerType = PICKER_TYPE_END;
        DialogFragment newFragment = new TimePickerFragment();
        newFragment.show(getFragmentManager(), "timePicker");
    }

    @Override
    public void onTimeSet(TimePicker view, int hour, int minute) {
        if (mCurrentPickerType == PICKER_TYPE_START) {
            pickerStartHour = hour;
            pickerStartMin = minute;
            tvStartTime.setText(convertToTimeToMySQLFormat(hour, minute));
        } else if (mCurrentPickerType == PICKER_TYPE_END) {
            pickerEndHour = hour;
            pickerEndMin = minute;
            tvEndTime.setText(convertToTimeToMySQLFormat(hour, minute));
        } else {
            Log.e(LOG_STRING, "Error: Invalid listener type for time set.");
        }
    }

    public void sendNewScheduleToCloud(View v) {
        HessSchedule schedule = new HessSchedule();
        schedule.EndTime = convertToTimeToMySQLFormat(pickerEndHour, pickerEndMin);
        schedule.StartTime = convertToTimeToMySQLFormat(pickerStartHour, pickerStartMin);
        PeakType peak = (PeakType)peakSpinner.getSelectedItem();
        WeekType week = (WeekType)weekSpinner.getSelectedItem();
        schedule.PeakTypeID = peak.getID();
        schedule.WeekTypeID = week.getID();

        JSONObject jsonObj = schedule.toJSON();
        JSONArray json = new JSONArray();
        json.put(jsonObj);

        VolleyRequest req = new VolleyRequest();
        req.postData(this, "", json);
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
}
