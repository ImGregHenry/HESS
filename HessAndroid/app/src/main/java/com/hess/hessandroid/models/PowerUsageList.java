package com.hess.hessandroid.models;

import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class PowerUsageList {
    private final static String LOG_STRING = "HESS_PowerUsageModel";
    public ArrayList<PowerUsage> PowerUsage;

    public PowerUsageList(ArrayList<PowerUsage> powerUsg) {
        PowerUsage = powerUsg;
    }

    public JSONArray toJSONArray() {
        JSONArray jArray = new JSONArray();

        for(PowerUsage p : PowerUsage)
            jArray.put(p.toJSON());

        return jArray;
    }
}
