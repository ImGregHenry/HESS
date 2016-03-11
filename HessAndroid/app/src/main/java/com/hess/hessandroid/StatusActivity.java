package com.hess.hessandroid;

import android.content.Intent;
import android.net.Uri;
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
import com.hess.hessandroid.volley.VolleyRequest;

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
        tv = (TextView) findViewById(R.id.tvTest);

        VolleyRequest vr = new VolleyRequest();
        //vr.getData(this);
        vr.postData(this, "", new JSONObject());

        Log.d(LOG_STRING, "Starting Session.  Max Messages: " + MAX_MESSAGES + ".");
        tv.setText("THIS");

    }
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
//        new Thread(new Runnable() {
//            @Override
//            public void run() {
//                // TODO Auto-generated method stub
//                while (true) {
//                    try {
//                        Thread.sleep(2000);
//                        mHandler.post(new Runnable() {
//
//                            @Override
//                            public void run() {
//                                // TODO Auto-generated method stub
//                                getData();                            }
//                        });
//                    } catch (Exception e) {
//                        // TODO: handle exception
//                    }
//                }
//            }
//        }).start();
////        getArrayData();
//    }


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
