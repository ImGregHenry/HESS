package com.hess.hessandroid;

import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.hess.hessandroid.models.BatteryStatus;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.models.PowerUsage;
import com.hess.hessandroid.volley.VolleyRequest;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;

public class StatusActivity extends AppCompatActivity implements
        VolleyRequest.VolleyReqCallbackGetBatteryStatus, VolleyRequest.VolleyReqCallbackGetPowerUsage, VolleyRequest.VolleyReqCallbackGetSchedule {
    private final static String LOG_STRING = "HESS_STATUS";
    //TextView tv;
    private TextView powerPercentVal;
    private ProgressBar progressBar;
    private TextView powerUsageVal;
    private TextView remainingTimeVal;

    private int powerPercent;
    private double totalPowerUsage = 1260.0;
    private double currentPowerUsage;
    private double powerPercentDec;
    private double remainingTime;
    private int remainingTimeHour;
    private int remainingTimeMinute;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        progressBar = (ProgressBar) findViewById(R.id.remainingPowerProgress);
        powerPercentVal = (TextView) findViewById(R.id.remainingPowerPercent);
        powerUsageVal = (TextView) findViewById(R.id.cUsage);
        remainingTimeVal = (TextView) findViewById(R.id.rTime);

        //Receive battery status every minute
        Timer timerBatteryStatus = new Timer();
        timerBatteryStatus.schedule(new TimerTask() {
            public void run() {
                requestBatteryStatus();
            }
        }, 0, 60*1000);

        //Receive power usage every minute
        //TODO: change minute to smaller interval
        Timer timerPowerUsage = new Timer();
        timerPowerUsage.schedule(new TimerTask() {
            public void run() {
                requestPowerUsage();
            }
        }, 0, 60*1000);

        requestHessScheduler();
    }

    @Override
    public void onVolleyGetBatteryStatusReady(BatteryStatus batteryStatus) {
        initializeBatteryStatus(batteryStatus);
    }

    private void initializeBatteryStatus(BatteryStatus batteryStatuses) {
        powerPercent = batteryStatuses.getPowerLevelPercent();
        Log.d(LOG_STRING, powerPercent + "%");

        powerPercentVal.setText(powerPercent + "%");
        progressBar.setProgress(powerPercent);
    }

    @Override
    public void onVolleyGetPowerUsageReady(PowerUsage powerUsage) {
        initializePowerUsage(powerUsage);
    }

    private void initializePowerUsage(PowerUsage powerUsages) {
        currentPowerUsage = powerUsages.getPowerUsage();
        Log.d(LOG_STRING, currentPowerUsage + "W");
        powerUsageVal.setText(currentPowerUsage + "W");

        //Remaining time calculation
        powerPercentDec = powerPercent/(double)100;
        remainingTime = (totalPowerUsage/currentPowerUsage)*powerPercentDec;
        remainingTimeHour = (int)remainingTime;
        remainingTimeMinute = (int)((remainingTime - remainingTimeHour)*60);
        Log.d(LOG_STRING, remainingTimeHour + "H " + remainingTimeMinute + "M");
        remainingTimeVal.setText(remainingTimeHour + "H " + remainingTimeMinute + "M");
    }

    public void onVolleyGetScheduleReady(HessScheduleList scheduleList) {
        // TODO: displayed schedule data
        initializeHessSchedule(scheduleList.Schedule);
    }

    private void initializeHessSchedule(ArrayList<HessSchedule> schedules) {
        Log.d(LOG_STRING, "Return list of schedulesL " + (schedules.get(0)).toString());
    }

    private void requestBatteryStatus() {
//        Log.d(LOG_STRING, "TESTING*************");

        VolleyRequest req = new VolleyRequest();
        req.getBatteryStatusData(this);
    }

    private void requestPowerUsage() {
        VolleyRequest req = new VolleyRequest();
        req.getPowerUsageData(this);
    }

    private void requestHessScheduler() {
        VolleyRequest req = new VolleyRequest();
        req.getSchedulerData(this);
    }

//    @Override
//    public boolean onCreateOptionsMenu(Menu menu) {
//        // Inflate the menu; this adds items to the action bar if it is present.
//        getMenuInflater().inflate(R.menu.menu_status, menu);
//        return true;
//    }
//
//    @Override
//    public boolean onOptionsItemSelected(MenuItem item) {
//        // Handle action bar item clicks here. The action bar will
//        // automatically handle clicks on the Home/Up button, so long
//        // as you specify a parent activity in AndroidManifest.xml.
//        int id = item.getItemId();
//
//        //noinspection SimplifiableIfStatement
//        if (id == R.id.action_settings) {
//            return true;
//        }
//
//        return super.onOptionsItemSelected(item);
//    }
}
