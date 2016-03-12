package com.hess.hessandroid;

import android.app.DialogFragment;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import com.hess.hessandroid.adapters.ScheduleArrayAdapter;
import com.hess.hessandroid.dialogs.TimePickerFragment;
import com.hess.hessandroid.enums.PeakType;
import com.hess.hessandroid.enums.WeekType;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.volley.VolleyRequest;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

public class ScheduleActivity extends AppCompatActivity implements TimePickerDialog.OnTimeSetListener, VolleyRequest.VolleyReqCallbackGetSchedule, VolleyRequest.VolleyReqCallbackPutSchedule {
    private final static String LOG_STRING = "HESS_Schedule";
    private static String PICKER_TYPE_START = "START";
    private static String PICKER_TYPE_END = "END";

    private int pickerStartHour = 1;
    private int pickerStartMin = 50;
    private int pickerEndHour = 2;
    private int pickerEndMin = 51;

    private Spinner peakSpinner;
    private Spinner weekSpinner;
    private TextView tvStartTime;
    private TextView tvEndTime;
    private ListView lvSchedule;
    private String mCurrentPickerType;
    private ScheduleArrayAdapter mArrayAdapter;


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

        requestHessScheduler();
    }


    private void initializeListView(ArrayList<HessSchedule> schedules) {
        lvSchedule = (ListView) findViewById(R.id.listViewSchedule);

//        ArrayList<HessSchedule> schedules = new ArrayList<HessSchedule>();
//        HessSchedule schedule = new HessSchedule();
//        schedule.EndTime = "123";
//        schedule.StartTime = "321";
//        schedule.WeekTypeID = 12;
//        schedule.PeakTypeID = 19;
//        schedules.add(schedule);

        if(mArrayAdapter != null)
            mArrayAdapter.clear();
        mArrayAdapter = new ScheduleArrayAdapter(this, schedules);


        lvSchedule.setAdapter(mArrayAdapter);
        lvSchedule.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                int itemPosition     = position;

                String  itemValue    = (String) lvSchedule.getItemAtPosition(position);

                Toast.makeText(getApplicationContext(),
                        "Position :" + itemPosition + "  ListItem : " + itemValue, Toast.LENGTH_LONG)
                        .show();
            }
        });
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

    @Override
    public void onVolleyGetScheduleReady(HessScheduleList scheduleList) {
        // TODO: displayed schedule data
        initializeListView(scheduleList.Schedule);
    }
    @Override
    public void onVolleyPutScheduleReady() {
        requestHessScheduler();
    }

    private void requestHessScheduler() {
        // Request data from cloud server
        VolleyRequest req = new VolleyRequest();
        req.getSchedulerData(this);
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
        req.postData(this, json);
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
