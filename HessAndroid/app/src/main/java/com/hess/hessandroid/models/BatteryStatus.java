package com.hess.hessandroid.models;

import android.util.Log;

import java.util.Date;

import org.json.JSONException;
import org.json.JSONObject;

public class BatteryStatus {
    private final static String LOG_STRING = "HESS_ScheduleModel";
    public int BatteryStatusID;
    public int PeakScheduleID;
    public Date RecordTime;
    public int IsEnabled;
    public int PowerLevelPercent;
}
