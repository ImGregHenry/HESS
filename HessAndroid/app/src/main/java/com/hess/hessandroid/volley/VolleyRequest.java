package com.hess.hessandroid.volley;

import android.content.Context;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.models.BatteryStatus;
import com.hess.hessandroid.models.PowerUsage;

import org.json.JSONArray;
import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.HashMap;
import java.util.Map;

public class VolleyRequest {
    private static String LOG_STRING = "HESS_VolleyReq";

    public interface VolleyReqCallbackGetSchedule {
        public void onVolleyGetScheduleReady(HessScheduleList list);
    }

    public interface VolleyReqCallbackPutSchedule {
        public void onVolleyPutScheduleReady();
    }

    public interface VolleyReqCallbackGetBatteryStatus {
        public void onVolleyGetBatteryStatusReady(BatteryStatus string);
    }

    public interface VolleyReqCallbackGetPowerUsage {
        public void onVolleyGetPowerUsageReady(PowerUsage string);
    }

    public void postData(final Context context, final JSONArray js) {
        String url2 = "http://hess.site88.net/HessCloudPutScheduler.php";

        StringRequest jsonObjReq = new StringRequest(
                Request.Method.POST, url2,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d(LOG_STRING, response.toString());

                        VolleyReqCallbackPutSchedule callback = (VolleyReqCallbackPutSchedule)context;
                        callback.onVolleyPutScheduleReady();
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.d(LOG_STRING, "Error: " + error.getMessage());
                    }
        }) {
            @Override
            protected Map<String,String> getParams(){
                Map<String,String> params = new HashMap<String, String>();
//                String json = "[{ \"PeakScheduleID\" : 59, \"WeekTypeID\" : 9, \"PeakTypeID\" : 9, \"StartTime\" : \"22:59:00\", \"EndTime\" : \"23:29:00\" },"
//                        + "{ \"PeakScheduleID\" : 57, \"WeekTypeID\" : 9, \"PeakTypeID\" : 9, \"StartTime\" : \"01:59:00\", \"EndTime\" : \"02:29:00\" }]"; //"IsDeleteSchedule" : 1,
//                params.put("JSON", json);

                params.put("JSON", js.toString());

                return params;
            }
        };
        Volley.newRequestQueue(context).add(jsonObjReq);
    }

    public void getSchedulerData(final Context context) {
        String url = "http://hess.site88.net/HessCloudGetScheduler.php";

        JsonObjectRequest jsonRequest = new JsonObjectRequest
                (Request.Method.GET, url, new JSONObject(), new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            Gson gson = new Gson();

                            Type listType = new TypeToken<HessScheduleList>() { }.getType();
                            HessScheduleList arrayList = gson.fromJson(response.toString(), listType);

                            VolleyReqCallbackGetSchedule callback = (VolleyReqCallbackGetSchedule)context;
                            callback.onVolleyGetScheduleReady(arrayList);
                        }
                        catch(Exception e) {
                            Log.e(LOG_STRING, e.getMessage());
                        }
                    }
                }, new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                    }
                });

        Volley.newRequestQueue(context).add(jsonRequest);
    }



    public void getBatteryStatusData(final Context context) {
        String url = "http://hess.site88.net/HessCloudGetBatteryStatus.php";

        JsonObjectRequest jsonRequest = new JsonObjectRequest
                (Request.Method.GET, url, new JSONObject(), new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        try {
                            Gson gson = new Gson();

                            Type listType = new TypeToken<BatteryStatus>() { }.getType();
                            BatteryStatus arrayString = gson.fromJson(response.toString(), listType);

                            VolleyReqCallbackGetBatteryStatus callback = (VolleyReqCallbackGetBatteryStatus)context;
                            callback.onVolleyGetBatteryStatusReady(arrayString);

                            //Log.d(LOG_STRING, list.get(0));
                        }
                        catch(Exception e) {
                            Log.e(LOG_STRING, e.getMessage());

                        }

                    }
                }, new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e(LOG_STRING, "ERROR: " + error.getMessage());
                    }
                });

        Volley.newRequestQueue(context).add(jsonRequest);
    }

    public void getPowerUsageData(final Context context) {
        String url = "http://hess.site88.net/HessCloudGetPowerUsage.php";

        JsonObjectRequest jsonRequest = new JsonObjectRequest
                (Request.Method.GET, url, new JSONObject(), new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        try {
                            Gson gson = new Gson();

                            Type listType = new TypeToken<PowerUsage>() { }.getType();
                            PowerUsage arrayString = gson.fromJson(response.toString(), listType);

                            VolleyReqCallbackGetPowerUsage callback = (VolleyReqCallbackGetPowerUsage)context;
                            callback.onVolleyGetPowerUsageReady(arrayString);
                        }
                        catch(Exception e) {
                            Log.e(LOG_STRING, e.getMessage());

                        }

                    }
                }, new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e(LOG_STRING, "ERROR: " + error.getMessage());
                    }
                });

        Volley.newRequestQueue(context).add(jsonRequest);
    }
}
