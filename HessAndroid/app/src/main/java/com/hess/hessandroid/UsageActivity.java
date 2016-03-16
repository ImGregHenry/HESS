package com.hess.hessandroid;

import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ForegroundColorSpan;
import android.util.Log;

import com.jjoe64.graphview.GraphView;
import com.jjoe64.graphview.Viewport;
import com.jjoe64.graphview.series.DataPoint;
import com.jjoe64.graphview.series.LineGraphSeries;

import android.widget.TextView;

import com.hess.hessandroid.models.PowerUsage;
import com.hess.hessandroid.models.PowerUsageList;
import com.hess.hessandroid.volley.VolleyRequest;

import java.util.ArrayList;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class UsageActivity extends AppCompatActivity implements VolleyRequest.VolleyReqCallbackGetPowerUsage {
    private final static String LOG_STRING = "HESS_USAGE";

    private TextView dailySaving;
    private TextView totalSaving;

    private double savingsOnPeak = 9.2; //cents/kWh
    private double savingsMidPeak = 4.5; //cents/kWh
    private double minuteInHour = 0.0167; //hour
    private double dailyPowerUsageOn;
    private double dailyPowerUsageMid;
    private double dailyEnergyOn;
    private double dailyEnergyMid;
    private double dailySavingsOn = 0;
    private double dailySavingsMid = 0;
    private double dailySavingsTotal;
    private double dailySavingsTotalDollar;
    private double totalEnergyOn;
    private double totalEnergyMid;
    private double totalSavingsOn = 0;
    private double totalSavingsMid = 0;
    private double totalSavingsTotal;
    private double totalSavingsTotalDollar;

    private DateFormat dateFormat;
    private Date recordDate;
    private Date currentDate;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_usage);

        ActionBar actionBar = getSupportActionBar();
        actionBar.setBackgroundDrawable(new ColorDrawable(Color.parseColor("#5c0f92")));
        Spannable text = new SpannableString(actionBar.getTitle());
        text.setSpan(new ForegroundColorSpan(Color.WHITE), 0, text.length(), Spannable.SPAN_INCLUSIVE_INCLUSIVE);
        actionBar.setTitle(text);

        dailySaving = (TextView) findViewById(R.id.dSaving);
        totalSaving = (TextView) findViewById(R.id.tSaving);


/*        GraphView graph = (GraphView) findViewById(R.id.graph);
        LineGraphSeries<DataPoint> series = new LineGraphSeries<DataPoint>(new DataPoint[]{
                new DataPoint(0, 1),
                new DataPoint(1, 5),
                new DataPoint(2, 3),
                new DataPoint(3, 2),
                new DataPoint(4, 6)
        });
        graph.addSeries(series);*/
    }

    @Override
    public void onVolleyGetPowerUsageReady(PowerUsageList powerUsageList) {
        Log.d(LOG_STRING, "1");
        dailySaving.setText("1");
        initializePowerUsage(powerUsageList.PowerUsage);
    }

    private void initializePowerUsage(ArrayList<PowerUsage> powerUsages) {
        Log.d(LOG_STRING, "2");
        totalSaving.setText("2");
        for (int i = 0; i < powerUsages.size(); i++) {
            try {
                dateFormat = new SimpleDateFormat("yyyy-MM-dd");
                recordDate = dateFormat.parse(powerUsages.get(i).RecordTime);
                Log.d(LOG_STRING, "record date: " + recordDate);
                currentDate = dateFormat.parse(dateFormat.format(new Date()));
                Log.d(LOG_STRING, "current date: " + currentDate);

                if (recordDate.equals(currentDate)) {
                    Log.d(LOG_STRING, "reached here");
                    if (powerUsages.get(i).PeakTypeID == 2) {
                        dailyEnergyOn += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                        dailySavingsOn = dailyEnergyOn * savingsOnPeak; //cents
                    }
                    if (powerUsages.get(i).PeakTypeID == 3) {
                        dailyEnergyMid += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                        dailySavingsMid = dailyEnergyMid * savingsMidPeak; //cents
                    }
                    dailySavingsTotal = dailySavingsOn + dailySavingsMid; //cents
                    dailySavingsTotalDollar = dailySavingsTotal / 100d; //dollar
                    Log.d(LOG_STRING, "Daily Saving: $" + dailySavingsTotalDollar);
                    dailySaving.setText("$" + dailySavingsTotalDollar);
                }

                if (powerUsages.get(i).PeakTypeID == 2) {
                    totalEnergyOn += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                    totalSavingsOn = totalEnergyOn * savingsOnPeak; //cents
                }
                if (powerUsages.get(i).PeakTypeID == 3) {
                    totalEnergyMid += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                    totalSavingsMid = totalEnergyMid * savingsMidPeak; //cents
                }
                totalSavingsTotal = totalSavingsOn + totalSavingsMid; //cents
                totalSavingsTotalDollar = totalSavingsTotal / 100d; //dollar
                Log.d(LOG_STRING, "Total Saving: $" + totalSavingsTotalDollar);
                totalSaving.setText("$" + totalSavingsTotalDollar);

            } catch (Exception e) {
                Log.e(LOG_STRING, e.getMessage());
            }
        }
    }
}