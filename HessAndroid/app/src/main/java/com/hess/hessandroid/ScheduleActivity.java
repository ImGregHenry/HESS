package com.hess.hessandroid;

import android.app.ActionBar;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.NavUtils;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ForegroundColorSpan;
import android.util.TypedValue;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;

import com.hess.hessandroid.adapters.ScheduleArrayAdapter;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.volley.VolleyRequest;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

public class ScheduleActivity extends Activity implements VolleyRequest.VolleyReqCallbackGetSchedule, VolleyRequest.VolleyReqCallbackPutSchedule {

    private final static String LOG_STRING = "HESS_Schedule";
    private final static int NEW_SCHEDULE_ACTIVITY_RESULT_ID = 1;
    private final static int UPDATE_SCHEDULE_ACTIVITY_RESULT_ID = 2;

    private ListView lvSchedule;
    private ScheduleArrayAdapter mArrayAdapter;
    private RelativeLayout lv1;
    private ImageView imgGraphOverlay;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_schedule);

        lv1 = (RelativeLayout) findViewById(R.id.linearGraphLayout);

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

    MyGraphView graphView;
    private void initializeGraphView(HessScheduleList scheduleList) {
        if(graphView != null) {
            graphView.redrawGraph(scheduleList);
        } else {
            graphView = new MyGraphView(this, scheduleList);
            graphView.getLayoutParams();
            lv1.addView(graphView);

            RelativeLayout.LayoutParams lp = (RelativeLayout.LayoutParams) graphView.getLayoutParams();
            lp.addRule(RelativeLayout.CENTER_HORIZONTAL);
            lp.width = 450;
            lp.height = 450;
            lp.topMargin = 88;
            graphView.setLayoutParams(lp);
            graphView.requestLayout();
        }
        imgGraphOverlay = (ImageView) findViewById(R.id.imgGraphOverlay);
        imgGraphOverlay.setImageResource(R.drawable.img_clock_hours);

        RelativeLayout.LayoutParams lp = (RelativeLayout.LayoutParams) imgGraphOverlay.getLayoutParams();
        lp.addRule(RelativeLayout.CENTER_HORIZONTAL);
        lp.width = 600;
        lp.height = 600;
        imgGraphOverlay.setLayoutParams(lp);
        imgGraphOverlay.requestLayout();
    }

    @Override
    public void onVolleyGetScheduleReady(HessScheduleList scheduleList) {
        initializeGraphView(scheduleList);
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
