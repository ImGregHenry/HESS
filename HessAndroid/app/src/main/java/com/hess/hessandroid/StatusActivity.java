package com.hess.hessandroid;

import android.app.Activity;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.ColorDrawable;
import android.os.Handler;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ForegroundColorSpan;
import android.util.Log;

import android.widget.RelativeLayout;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;


import com.hess.hessandroid.models.BatteryStatus;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.models.PowerUsage;
import com.hess.hessandroid.models.PowerUsageList;
import com.hess.hessandroid.volley.VolleyRequest;

import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import java.lang.Math;

public class StatusActivity extends Activity implements
        VolleyRequest.VolleyReqCallbackGetBatteryStatus, VolleyRequest.VolleyReqCallbackGetPowerUsage, VolleyRequest.VolleyReqCallbackGetSchedule {
    private final static String LOG_STRING = "HESS_STATUS";
    private TextView powerPercentVal;
    private ImageView batteryOverlay;
    private ProgressBar progressBar;
    private TextView powerUsageVal;
    private TextView remainingTimeVal;
    private TextView batteryTimeText;
    private TextView currentPowerUsageTime;

    private MyGraphView graphView;
    private RelativeLayout lv1;

    private double powerPercent;

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

    private boolean hit;
    private long closestEnd;
    private long min = -1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        lv1 = (RelativeLayout) findViewById(R.id.relativeGraphLayout);

        powerPercentVal = (TextView) findViewById(R.id.remainingPowerPercent);
        powerUsageVal = (TextView) findViewById(R.id.cUsage);
        remainingTimeVal = (TextView) findViewById(R.id.rTime);
        batteryTimeText = (TextView) findViewById(R.id.remainingTime);
        currentPowerUsageTime = (TextView) findViewById(R.id.uTime);

        //Receive battery status, power usage every minute
        Timer timer = new Timer();
        timer.schedule(new TimerTask() {
            public void run() {
                requestBatteryStatus();
                requestPowerUsage();
                requestHessScheduler();
            }
        }, 0, 60*1000);


//        requestBatteryStatus();
//        requestPowerUsage();
//        requestHessScheduler();

    }

    @Override
    public void onVolleyGetBatteryStatusReady(BatteryStatus batteryStatus) {
        initializeBatteryStatus(batteryStatus);
    }

    private void initializeBatteryStatus(BatteryStatus batteryStatuses) {
/*        RelativeLayout.LayoutParams lp = (RelativeLayout.LayoutParams) graphView.getLayoutParams();
        lp.addRule(RelativeLayout.CENTER_HORIZONTAL);
        lp.width = 450;
        lp.height = 450;
        lp.topMargin = 75;
        graphView.setLayoutParams(lp);
        graphView.requestLayout();*/

        batteryOverlay = (ImageView) findViewById(R.id.imgBattOverlay);
        batteryOverlay.setImageResource(R.drawable.battery_outline);
        progressBar = (ProgressBar) findViewById(R.id.remainingPowerProgress);
        //progressBar.getIndeterminateDrawable().setColorFilter(Color.parseColor("#5c0f92"), PorterDuff.Mode.SRC_IN);

        RelativeLayout.LayoutParams lp = (RelativeLayout.LayoutParams) batteryOverlay.getLayoutParams();
        lp.addRule(RelativeLayout.CENTER_HORIZONTAL);
        lp.addRule(RelativeLayout.CENTER_VERTICAL);

        batteryOverlay.setLayoutParams(lp);
        batteryOverlay.requestLayout();

        powerPercent = batteryStatuses.getPowerLevelPercent();
        Log.d(LOG_STRING, Math.round((powerPercent*100.0) * 100.0) / 100.0 + "%");

        powerPercentVal.setText( Math.round((powerPercent*100.0) * 100.0) / 100.0 + "%");
        progressBar.setProgress((int) (powerPercent * 100));
    }

    @Override
    public void onVolleyGetPowerUsageReady(PowerUsageList powerUsageList) {
        initializePowerUsage(powerUsageList.PowerUsage);
    }

    private void initializePowerUsage(ArrayList<PowerUsage> powerUsages) {
        currentPowerUsage = powerUsages.get(powerUsages.size() - 1).PowerUsageWatt;
        Log.d(LOG_STRING, Math.round((currentPowerUsage) * 100.0) / 100.0 + "W");
        powerUsageVal.setText(Math.round((currentPowerUsage) * 100.0) / 100.0 + "W");

    }

    public void onVolleyGetScheduleReady(HessScheduleList scheduleList) {
        initializeHessSchedule(scheduleList.Schedule);
    }

    private void initializeHessSchedule(ArrayList<HessSchedule> schedules) {
        if(schedules.size() > 0) {
            for (int i = 0; i < schedules.size(); i++) {
                //Remaining time calculation when in onpeak or midpeak-enabled
                if (schedules.get(i).PeakTypeID == 2 || schedules.get(i).PeakTypeID == 3) {
                    try {
                        dateFormat = new SimpleDateFormat("HH:mm");
                        startTime = dateFormat.parse(schedules.get(i).StartTime);
                        endTime = dateFormat.parse(schedules.get(i).EndTime);
                        currentTime = dateFormat.parse(dateFormat.format(new Date()));

                        if (startTime.before(currentTime) && endTime.after(currentTime)) {
                            hit = true;
                            //powerPercentDec = powerPercent / (double) 100;
                            remainingTime = (totalPowerUsage / currentPowerUsage) * (double) powerPercent;
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
                        if (currentTime.after(endTime)) {
                            closestEnd = currentTime.getTime() - endTime.getTime();
                            if (min > closestEnd || min == -1) {
                                min = closestEnd;
                            }
                        }
                        else if (currentTime.before(endTime)) {
                            batteryTimeText.setText("Time Unavailable ");
                            remainingTimeVal.setText("");
                        }
                    } catch (Exception e) {
                        Log.e(LOG_STRING, e.getMessage());
                    }
                }
            }
            //Time to full calculation
            if (!hit && min != -1) {
                try {
               /* dateFormat = new SimpleDateFormat("HH:mm");
                startTime = dateFormat.parse(schedules.get(i).StartTime);
                endTime = dateFormat.parse(schedules.get(i).EndTime);
                currentTime = dateFormat.parse(dateFormat.format(new Date()));

                startChargingTime = dateFormat.parse(schedules.get(i).StartTime);*/
                    timeToFullMS = 25500000 - (min);
                    timeToFullMin = (int) ((timeToFullMS / 60000) % 60);
                    timeToFullHour = (int) (timeToFullMS / 3600000);
                    batteryTimeText.setText("Time Until Full: ");

                    if (timeToFullMS <= 0) {
                        remainingTimeVal.setText("Charged");
                    }
                    else {
                        Log.d(LOG_STRING, "Time Until Full: " + timeToFullHour + ":" + timeToFullMin);
                        remainingTimeVal.setText(timeToFullHour + ":" + timeToFullMin);
                    }
                } catch (Exception e) {
                    Log.e(LOG_STRING, e.getMessage());
                }
            }
        }
        else {
            batteryTimeText.setText("Time Unavailable ");
            remainingTimeVal.setText("");
        }
    }

    private void requestBatteryStatus() {
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

}
