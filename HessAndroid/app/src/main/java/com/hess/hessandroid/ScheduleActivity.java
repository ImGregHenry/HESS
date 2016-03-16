package com.hess.hessandroid;

import android.content.Intent;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.NavUtils;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ForegroundColorSpan;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import com.hess.hessandroid.adapters.ScheduleArrayAdapter;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.volley.VolleyRequest;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

public class ScheduleActivity extends AppCompatActivity implements VolleyRequest.VolleyReqCallbackGetSchedule, VolleyRequest.VolleyReqCallbackPutSchedule {

    private final static String LOG_STRING = "HESS_Schedule";
    private final static int NEW_SCHEDULE_ACTIVITY_RESULT_ID = 1;
    private final static int UPDATE_SCHEDULE_ACTIVITY_RESULT_ID = 2;

    private ListView lvSchedule;
    private ScheduleArrayAdapter mArrayAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_schedule);

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fabAdd);
        fab.setImageResource(R.drawable.icon_add);
        fab.setBackgroundResource(R.color.hess_purple);

        ActionBar actionBar = getSupportActionBar();
        actionBar.setBackgroundDrawable(new ColorDrawable(Color.parseColor("#5c0f92")));
        Spannable text = new SpannableString(actionBar.getTitle());
        text.setSpan(new ForegroundColorSpan(Color.WHITE), 0, text.length(), Spannable.SPAN_INCLUSIVE_INCLUSIVE);
        actionBar.setTitle(text);

        requestHessScheduler();
    }

    public void startNewScheduleActivity(View v) {
        Intent intent = new Intent(this, SetScheduleActivity.class);
        intent.putExtra("IsNew", true);
        startActivityForResult(intent, NEW_SCHEDULE_ACTIVITY_RESULT_ID);
    }

    private void initializeListView(ArrayList<HessSchedule> schedules) {
        lvSchedule = (ListView) findViewById(R.id.listViewSchedule);

        if(mArrayAdapter != null)
            mArrayAdapter.clear();
        mArrayAdapter = new ScheduleArrayAdapter(this, schedules, this);

        lvSchedule.setAdapter(mArrayAdapter);
    }

    @Override
    public void onVolleyGetScheduleReady(HessScheduleList scheduleList) {
        initializeListView(scheduleList.Schedule);
    }

    @Override
    public void onVolleyPutScheduleReady() {
        requestHessScheduler();
    }

    private void requestHessScheduler() {
        VolleyRequest req = new VolleyRequest();
        req.getSchedulerData(this);
    }

    public void sendNewScheduleToCloud(HessSchedule schedule) {
        JSONObject jsonObj = schedule.toJSON();
        JSONArray json = new JSONArray();
        json.put(jsonObj);

        VolleyRequest req = new VolleyRequest();
        req.postData(this, json);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (resultCode == RESULT_OK) {
            HessSchedule schedule = (HessSchedule)data.getExtras().get("SCHEDULE");
            sendNewScheduleToCloud(schedule);
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            // Respond to the action bar's Up/Home button
            case android.R.id.home:
                NavUtils.navigateUpFromSameTask(this);
                return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
