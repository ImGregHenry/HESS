package com.hess.hessandroid.models;

import android.util.Log;
import com.google.gson.annotations.SerializedName;

public class BatteryStatus {
    private final static String LOG_STRING = "HESS_BatteryStatusModel";
    public int PeakScheduleID;
    public String RecordTime;
    public int IsEnabled;
    @SerializedName("PowerLevelPercent")
    public double PowerLevelPercent;


    public Double getPowerLevelPercent() {
        return PowerLevelPercent;
    }
}
