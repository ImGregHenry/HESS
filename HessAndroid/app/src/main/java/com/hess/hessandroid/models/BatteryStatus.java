package com.hess.hessandroid.models;

import android.util.Log;
import com.google.gson.annotations.SerializedName;

import org.json.JSONException;
import org.json.JSONObject;

public class BatteryStatus {
    private final static String LOG_STRING = "HESS_ScheduleModel";
    public int PeakScheduleID;
    public String RecordTime;
    public int IsEnabled;
    @SerializedName("PowerLevelPercent")
    public int PowerLevelPercent;


    public Integer getPowerLevelPercent() {
        return PowerLevelPercent;
    }


//    public JSONObject toJSON() {
//        try {
//            JSONObject json = new JSONObject();
//            // Don't add the json ID field if it isn't populated
//            json.put("PeakScheduleID", PeakScheduleID);
//            json.put("RecordTime", RecordTime);
//            json.put("IsEnabled", IsEnabled);
//            json.put("PowerLevelPercent", PowerLevelPercent);
//            return json;
//        } catch (JSONException e) {
//            Log.e(LOG_STRING, "JSON ERROR: Unable to parse. Message: " + e.getMessage());
//        }
//        return new JSONObject();
//    }

}
