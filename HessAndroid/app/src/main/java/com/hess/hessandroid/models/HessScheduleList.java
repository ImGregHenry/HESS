package com.hess.hessandroid.models;

import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Greg'sMonster on 11-Mar-16.
 */
public class HessScheduleList {
    private final static String LOG_STRING = "HESS_ScheduleModel";
    public ArrayList<HessSchedule> Schedule;

    public HessScheduleList(ArrayList<HessSchedule> sched) {
        Schedule = sched;
    }

    public JSONArray toJSONArray() {
        JSONArray jArray = new JSONArray();

        for(HessSchedule s : Schedule)
           jArray.put(s.toJSON());

        return jArray;
    }
}
