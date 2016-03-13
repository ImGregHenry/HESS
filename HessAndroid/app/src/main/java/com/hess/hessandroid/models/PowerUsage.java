package com.hess.hessandroid.models;

import android.util.Log;
import com.google.gson.annotations.SerializedName;

public class PowerUsage {
    private final static String LOG_STRING = "HESS_PowerUsageModel";
    public int PowerUsageID;
    public int PeakTypeID;
    public String RecordTime;
    @SerializedName("PowerUsageWatt")
    public double PowerUsage;


    public Double getPowerUsage() {
        return PowerUsage;
    }
}
