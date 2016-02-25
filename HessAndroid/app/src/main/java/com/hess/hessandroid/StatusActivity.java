package com.hess.hessandroid;

import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.format.Time;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.google.gson.reflect.TypeToken;
import com.hess.hessandroid.models.StatusModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.concurrent.TimeUnit;

public class StatusActivity extends AppCompatActivity {
    String LOG_STRING = "HESSz";
    int MAX_MESSAGES = 10;
    TextView tv;

    Handler mHandler = new Handler();



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);
        tv = (TextView)findViewById(R.id.tvTest);


        Log.d(LOG_STRING, "Starting Session.  Max Messages: " + MAX_MESSAGES + ".");
tv.setText("THIS");
        //initialize();
//
//        for(int i = 0; i < MAX_MESSAGES; i++) {
//            try {
//                getData();
//                Thread.sleep(1900);
//            }
//            catch(Exception e) {
//
//            }
//        }
        new Thread(new Runnable() {
            @Override
            public void run() {
                // TODO Auto-generated method stub
                while (true) {
                    try {
                        Thread.sleep(2000);
                        mHandler.post(new Runnable() {

                            @Override
                            public void run() {
                                // TODO Auto-generated method stub
                                getData();                            }
                        });
                    } catch (Exception e) {
                        // TODO: handle exception
                    }
                }
            }
        }).start();
//        getArrayData();
    }


    java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
    java.text.SimpleDateFormat sdfMS = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss.SSS");

    //    StatusModel list;
    public void getData() {
        String url = "http://hess.site88.net/HESSGetBatteryStatus.php";


        JsonObjectRequest jsonRequest = new JsonObjectRequest
                (Request.Method.GET, url, null, new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                    // the response is already constructed as a JSONObject!
                        try {

                            JSONArray result = response.getJSONArray("BatteryStatus");
                            String recordTime = result.getJSONObject(0).getString("RecordTime");
                            String cloudRecordTime = result.getJSONObject(0).getString("CloudRecordTime");
                            String cloudRecordTimeMS = result.getJSONObject(0).getString("CloudRecordTimeMS");
                            String recordTimeMS = result.getJSONObject(0).getString("RecordTimeMS");


                            Date piTime = sdfMS.parse(recordTime + "." + recordTimeMS);
                            Date cloudTime = sdfMS.parse(cloudRecordTime + "." + cloudRecordTimeMS);
                            Date mobileTime = new Date();


                            TimeUnit unit = TimeUnit.MILLISECONDS;
                            long piToCloud = cloudTime.getTime() - piTime.getTime();
                            long cloudToMobile = Math.abs(mobileTime.getTime() - cloudTime.getTime());

                            Log.d(LOG_STRING, piToCloud + "," + cloudToMobile);

//                            Log.d(LOG_STRING, "MOBILE: " + mobileTime.getTime());
                        }
                        catch(JSONException e) {
//                            e.printStackTrace();
                            Log.d(LOG_STRING, "SKIPPED, SKIPPED");

                        }
                        catch(Exception e) {
//                            e.printStackTrace();
                            Log.d(LOG_STRING, "SKIPPED, SKIPPED");

                        }
//                        Gson gson = new Gson();
//                        StatusModel arrayList = new StatusModel();
//                        Type listType = new TypeToken<StatusModel>() { }.getType();
//                        list = gson.fromJson(response.toString(), listType);
//                        System.out.println("RESULT: " + list.batteryStatus.get(0).RecordTime);
                    }
                }, new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                    }
                });

        Volley.newRequestQueue(this).add(jsonRequest);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_status, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
