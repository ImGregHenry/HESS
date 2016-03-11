package com.hess.hessandroid.models;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by Greg'sMonster on 11-Mar-16.
 */
public class HessSchedule {
    private final static String LOG_STRING = "HESS_ScheduleModel";
    public int PeakScheduleID = -1;
    public int WeekTypeID;
    public int PeakTypeID;
    public String StartTime;
    public String EndTime;

    public JSONObject toJSON() {
        try {
            JSONObject json = new JSONObject();
            if(PeakScheduleID != -1)
                json.put("PeakScheduleID", PeakScheduleID);
            json.put("WeekTypeID", WeekTypeID);
            json.put("PeakTypeID", PeakTypeID);
            json.put("StartTime", StartTime);
            json.put("EndTime", EndTime);
            return json;
        } catch (JSONException e) {
            Log.e(LOG_STRING, "JSON ERROR: Unable to parse. Message: " + e.getMessage());
        }
        return null;
    }

}
