package com.hess.hessandroid.models;

import android.util.Log;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.Serializable;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class HessSchedule implements Serializable {
    private final static String LOG_STRING = "HESS_ScheduleModel";
    public int PeakScheduleID = -1;
    public int WeekTypeID;
    public int PeakTypeID;
    public String StartTime;
    public String EndTime;
    public boolean IsDeleted = false;
    SimpleDateFormat outputDateFormat = new SimpleDateFormat("HH:mma");
    SimpleDateFormat inputDateFormat = new SimpleDateFormat("hh:mm:ss");

    public String getStartTimeAMPM() {
        try {
            Date date = inputDateFormat.parse(StartTime);
            return outputDateFormat.format(date);
        } catch(Exception e) {
            Log.d(LOG_STRING, "UNABLE TO PARSE START TIME.");
            return StartTime;
        }
    }

    public String getEndTimeAMPM() {
        try {
            Date date = inputDateFormat.parse(EndTime);
            return outputDateFormat.format(date);
        } catch(Exception e) {
            Log.d(LOG_STRING, "UNABLE TO PARSE END TIME.");
            return StartTime;
        }
    }

    public boolean isValidTimes(String startTime, String endTime) {
        return true;
    }

    public JSONObject toJSON() {
        try {
            JSONObject json = new JSONObject();
            // Don't add the json ID field if it isn't populated
            if(PeakScheduleID != -1)
                json.put("PeakScheduleID", PeakScheduleID);
            if(IsDeleted)
                json.put("IsDeleted", true);
            json.put("WeekTypeID", WeekTypeID);
            json.put("PeakTypeID", PeakTypeID);
            json.put("StartTime", StartTime);
            json.put("EndTime", EndTime);
            return json;
        } catch (JSONException e) {
            Log.e(LOG_STRING, "JSON ERROR: Unable to parse. Message: " + e.getMessage());
        }
        return new JSONObject();
    }

}
