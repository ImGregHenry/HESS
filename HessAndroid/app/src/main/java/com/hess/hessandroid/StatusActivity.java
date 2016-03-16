package com.hess.hessandroid;

import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

import android.widget.ProgressBar;
import android.widget.TextView;

import com.hess.hessandroid.models.BatteryStatus;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.models.PowerUsage;
import com.hess.hessandroid.models.PowerUsageList;
import com.hess.hessandroid.volley.VolleyRequest;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class StatusActivity extends AppCompatActivity implements
        VolleyRequest.VolleyReqCallbackGetBatteryStatus, VolleyRequest.VolleyReqCallbackGetPowerUsage, VolleyRequest.VolleyReqCallbackGetSchedule {
    private final static String LOG_STRING = "HESS_STATUS";
    private TextView powerPercentVal;
    private ProgressBar progressBar;
    private TextView powerUsageVal;
    private TextView remainingTimeVal;
    private TextView batteryTimeText;
    private TextView currentPowerUsageTime;

    private int powerPercent;

    private double totalPowerUsage = 1260.0;
    private double currentPowerUsage;

    private DateFormat dateFormat;
    private Date currentTime;
    private Date startTime;
    private Date endTime;
    private Date startChargingTime;

    private long timeToFullMS;
    private int timeToFullMin;
    private int timeToFullHour;

    private double powerPercentDec;
    private double remainingTime;
    private int remainingTimeHour;
    private int remainingTimeMinute;

    private long currentUsageTimeMS;
    private int currentUsageTimeMin;
    private int currentUsageTimeHour;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        progressBar = (ProgressBar) findViewById(R.id.remainingPowerProgress);
        powerPercentVal = (TextView) findViewById(R.id.remainingPowerPercent);
        powerUsageVal = (TextView) findViewById(R.id.cUsage);
        remainingTimeVal = (TextView) findViewById(R.id.rTime);
        batteryTimeText = (TextView) findViewById(R.id.remainingTime);
        currentPowerUsageTime = (TextView) findViewById(R.id.uTime);

        //Receive battery status every minute
        Timer timerBatteryStatus = new Timer();
        timerBatteryStatus.schedule(new TimerTask() {
            public void run() {
                requestBatteryStatus();
            }
        }, 0, 60*1000);

        //Receive power usage every minute
        Timer timerPowerUsage = new Timer();
        timerPowerUsage.schedule(new TimerTask() {
            public void run() {
                requestPowerUsage();
            }
        }, 0, 60*1000);

//        requestBatteryStatus();
//        requestPowerUsage();
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
    public void onVolleyGetPowerUsageReady(PowerUsageList powerUsageList) {
        initializePowerUsage(powerUsageList.PowerUsage);
    }

    private void initializePowerUsage(ArrayList<PowerUsage> powerUsages) {
        currentPowerUsage = powerUsages.get(powerUsages.size() - 1).PowerUsageWatt;
        Log.d(LOG_STRING, currentPowerUsage + "W");
        powerUsageVal.setText(currentPowerUsage + "W");

    }

    public void onVolleyGetScheduleReady(HessScheduleList scheduleList) {
        initializeHessSchedule(scheduleList.Schedule);
    }

    private void initializeHessSchedule(ArrayList<HessSchedule> schedules) {
        for(int i = 0; i < schedules.size(); i++) {
            //Time to full calculation
            if(schedules.get(i).PeakTypeID == 1) {
                try {
                    dateFormat = new SimpleDateFormat("HH:mm");
                    startTime = dateFormat.parse(schedules.get(i).StartTime);
                    endTime = dateFormat.parse(schedules.get(i).EndTime);
                    currentTime = dateFormat.parse(dateFormat.format(new Date()));

                    if (startTime.before(currentTime) && endTime.after(currentTime)) {
                        startChargingTime = dateFormat.parse(schedules.get(i).StartTime);
                        timeToFullMS = 28800000 - (currentTime.getTime() - startChargingTime.getTime());
                        timeToFullMin = (int) ((timeToFullMS / 60000) % 60);
                        timeToFullHour = (int) (timeToFullMS / 3600000);
                        Log.d(LOG_STRING, "Time Until Full: " + timeToFullHour + ":" + timeToFullMin);
                        batteryTimeText.setText("Time Until Full: ");
                        remainingTimeVal.setText(timeToFullHour + ":" + timeToFullMin);
                    }
                }
                catch (Exception e){
                    Log.e(LOG_STRING, e.getMessage());
                }
            }
            //Remaining time calculation when in onpeak or midpeak-enabled
            else if(schedules.get(i).PeakTypeID == 2 || schedules.get(i).PeakTypeID == 3) {
                try {
                    dateFormat = new SimpleDateFormat("HH:mm");
                    startTime = dateFormat.parse(schedules.get(i).StartTime);
                    endTime = dateFormat.parse(schedules.get(i).EndTime);
                    currentTime = dateFormat.parse(dateFormat.format(new Date()));

                    if (startTime.before(currentTime) && endTime.after(currentTime)) {
                        powerPercentDec = powerPercent / (double) 100;
                        remainingTime = (totalPowerUsage / currentPowerUsage) * powerPercentDec;
                        remainingTimeHour = (int) remainingTime;
                        remainingTimeMinute = (int) ((remainingTime - remainingTimeHour) * 60);
                        Log.d(LOG_STRING, "Time Remaining: " + remainingTimeHour + ":" + remainingTimeMinute);
                        batteryTimeText.setText("Time Remaining at " + currentPowerUsage + "W: ");
                        remainingTimeVal.setText(remainingTimeHour + ":" + remainingTimeMinute);

                        startChargingTime = dateFormat.parse(schedules.get(i).StartTime);
                        currentUsageTimeMS = (currentTime.getTime() - startChargingTime.getTime());
                        currentUsageTimeMin = (int) ((currentUsageTimeMS / 60000) % 60);
                        currentUsageTimeHour = (int) (currentUsageTimeMS / 3600000);
                        Log.d(LOG_STRING, "Current Usage Time: " + currentUsageTimeHour + ":" + currentUsageTimeMin);
                        currentPowerUsageTime.setText(currentUsageTimeHour + ":" + currentUsageTimeMin);
                    }
                }
                catch (Exception e){
                    Log.e(LOG_STRING, e.getMessage());
                }

            }
        }
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
