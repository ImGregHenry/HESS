package com.hess.hessandroid.models;

import android.util.Log;
import com.google.gson.annotations.SerializedName;

import org.json.JSONException;
import org.json.JSONObject;

public class PowerUsage {
    private final static String LOG_STRING = "HESS_PowerUsageModel";
    public int PowerUsageID;
    //@SerializedName("PeakTypeID")
    public int PeakTypeID;
    public String RecordTime;
    //@SerializedName("PowerUsageWatt")
    public double PowerUsageWatt;

    public JSONObject toJSON() {
        try {
            JSONObject json = new JSONObject();
            json.put("PowerUsageID", PowerUsageID);
            json.put("PeakTypeID", PeakTypeID);
            json.put("RecordTime", RecordTime);
            json.put("PowerUsage", PowerUsageWatt);
            return json;
        } catch (JSONException e) {
            Log.e(LOG_STRING, "JSON ERROR: Unable to parse. Message: " + e.getMessage());
        }
        return new JSONObject();
    }

/*    public Double getPowerUsage() {
        return PowerUsage;
    }

    public Integer getPowerUsagePeakID() {
        return PeakTypeID;
    }*/
}
