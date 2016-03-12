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

import org.json.JSONArray;
import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Greg'sMonster on 10-Mar-16.
 */
public class VolleyRequest {
    private static String LOG_STRING = "HESS_VolleyReq";

    public interface VolleyRequestCallback {
        public void onVolleyGetScheduleReady(HessScheduleList list);
        public void onVolleyPutScheduleReady();
        //public void onVolleyGetBatteryStatusReady(BatteryStatusList list);
    }

    public void postData(final Context context, final JSONArray js) {
        String url2 = "http://hess.site88.net/HessCloudPutScheduler.php";

        StringRequest jsonObjReq = new StringRequest(
                Request.Method.POST, url2,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d(LOG_STRING, response.toString());

                        VolleyRequestCallback callback = (VolleyRequestCallback)context;
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

                            VolleyRequestCallback callback = (VolleyRequestCallback)context;
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



    public void getData(Context context) {
        String url = "hess.site88.net/HessCloudGetBatteryStatus.php";

        JsonObjectRequest jsonRequest = new JsonObjectRequest
                (Request.Method.GET, url, null, new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        try {
//                            Gson gson = new Gson();
//                            StatusModel arrayList = new StatusModel();
//                            Type listType = new TypeToken<StatusModel>() { }.getType();
                            //list = gson.fromJson(response.toString(), listType);
                            //System.out.println("RESULT: " + list.batteryStatus.get(0).RecordTime);
                            Log.d(LOG_STRING, "RECEIVED SUM DATAZ!");
                        }
//                        catch(JSONException e) {
////                            e.printStackTrace();
//                            Log.d(LOG_STRING, "SKIPPED, SKIPPED");
//
//                        }

                        catch(Exception e) {
//                            e.printStackTrace();
                            Log.d(LOG_STRING, "SKIPPED, SKIPPED");

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
}
