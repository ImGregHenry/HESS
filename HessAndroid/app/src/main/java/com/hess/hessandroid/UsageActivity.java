package com.hess.hessandroid;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
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

    private TextView totalPower;
    private TextView dailySaving;
    private TextView totalSaving;

    private GraphView graph;

    private double savingsOnPeak = 9.2; //cents/kWh
    private double savingsMidPeak = 4.5; //cents/kWh
    private double minuteInHour = 0.0167; //hour
    private double dailyEnergyOn;
    private double dailyEnergyMid;
    private double dailySavingsOn = 0.0;
    private double dailySavingsMid = 0.0;
    private double dailySavingsTotal;
    private double dailySavingsTotalDollar;
    private double totalEnergyOn;
    private double totalEnergyMid;
    private double totalSavingsOn = 0.0;
    private double totalSavingsMid = 0.0;
    private double totalSavingsTotal;
    private double totalSavingsTotalDollar;
    private double totalPowerUsageOn = 0.0;
    private double totalPowerUsageMid = 0.0;
    private double totalPowerUsage;

    private DateFormat dateFormat;
    private Date recordDate;
    private Date currentDate;

    private LineGraphSeries<DataPoint> series;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_usage);

        totalPower = (TextView) findViewById(R.id.tPowUsage);
        dailySaving = (TextView) findViewById(R.id.dSaving);
        totalSaving = (TextView) findViewById(R.id.tSaving);
        graph = (GraphView) findViewById(R.id.graph);

        requestPowerUsage();
    }

    @Override
    public void onVolleyGetPowerUsageReady(PowerUsageList powerUsageList) {
        initializePowerUsage(powerUsageList.PowerUsage);
    }

    private void initializePowerUsage(ArrayList<PowerUsage> powerUsages) {
        for (int i = 0; i < powerUsages.size(); i++) {
            if(powerUsages.get(i).PeakTypeID == 2 || powerUsages.get(i).PeakTypeID == 3) {
                series = new LineGraphSeries<DataPoint>(new DataPoint[]{
                        new DataPoint(i, powerUsages.get(i).PowerUsageWatt),
                });
                graph.addSeries(series);
            }

            try {
                dateFormat = new SimpleDateFormat("yyyy-MM-dd");
                recordDate = dateFormat.parse(powerUsages.get(i).RecordTime);
                currentDate = dateFormat.parse(dateFormat.format(new Date()));

                if (recordDate.equals(currentDate)) {
                    if (powerUsages.get(i).PeakTypeID == 2) {
                        dailyEnergyOn += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                        dailySavingsOn = dailyEnergyOn * savingsOnPeak; //cents
                    }
                    if (powerUsages.get(i).PeakTypeID == 3) {
                        dailyEnergyMid += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                        dailySavingsMid = dailyEnergyMid * savingsMidPeak; //cents
                    }

                }

                if (powerUsages.get(i).PeakTypeID == 2) {
                    totalPowerUsageOn += powerUsages.get(i).PowerUsageWatt / 1000d; //kW

                    totalEnergyOn += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                    totalSavingsOn = totalEnergyOn * savingsOnPeak; //cents
                }
                if (powerUsages.get(i).PeakTypeID == 3) {
                    totalPowerUsageMid += powerUsages.get(i).PowerUsageWatt / 1000d; //kW

                    totalEnergyMid += ((powerUsages.get(i).PowerUsageWatt / 1000d) * minuteInHour); //kWh
                    totalSavingsMid = totalEnergyMid * savingsMidPeak; //cents
                }


            } catch (Exception e) {
                Log.e(LOG_STRING, e.getMessage());
            }
        }

        dailySavingsTotal = dailySavingsOn + dailySavingsMid; //cents
        dailySavingsTotalDollar = dailySavingsTotal / 100d; //dollar
        Log.d(LOG_STRING, "Daily Saving: $" + dailySavingsTotalDollar);
        dailySaving.setText("$" + dailySavingsTotalDollar);

        totalPowerUsage = totalPowerUsageOn + totalPowerUsageMid; //kW
        Log.d(LOG_STRING, "Total Power Usage: " + totalPowerUsage + "kW");
        totalPower.setText(totalPowerUsage + "kW");

        totalSavingsTotal = totalSavingsOn + totalSavingsMid; //cents
        totalSavingsTotalDollar = totalSavingsTotal / 100d; //dollar
        Log.d(LOG_STRING, "Total Saving: $" + totalSavingsTotalDollar);
        totalSaving.setText("$" + totalSavingsTotalDollar);

    }

    private void requestPowerUsage() {
        VolleyRequest req = new VolleyRequest();
        req.getPowerUsageData(this);
    }
}