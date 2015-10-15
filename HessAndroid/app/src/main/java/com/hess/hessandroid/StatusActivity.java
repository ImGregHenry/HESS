package com.hess.hessandroid;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.reflect.TypeToken;
import com.hess.hessandroid.models.TestModel;

import org.json.JSONArray;
import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;

public class StatusActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        initialize();
        //getData();
        getArrayData();
    }

    public void initialize() {
        TextView tvHeaderHome = (TextView)findViewById(R.id.tvHeaderMyHome);
        tvHeaderHome.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                Log.d("StatusActivity", "Starting MyHome activity.");
                Intent intent = new Intent(StatusActivity.this, MyHomeActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });

        TextView tvStatus = (TextView)findViewById(R.id.tvHeaderStatus);
        tvStatus.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                Log.d("StatusActivity", "Starting Status activity.");
                Intent intent = new Intent(StatusActivity.this, StatusActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });
    }

    public void getObjectData() {
        final TextView tvBatteryTest = (TextView)findViewById(R.id.tvBatteryTestStatus);
        tvBatteryTest.setText("thisvalue");

        // Instantiate the RequestQueue.
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "http://192.168.43.234:8000/api/status";

        // Request a string response from the provided URL.
        JsonObjectRequest stringRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        Gson gson = new Gson();
                        TestModel prod = gson.fromJson(response.toString(), TestModel.class);

                        tvBatteryTest.setText("Worked");
                        Log.d("StatusActivity", "Response: " + prod);
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                tvBatteryTest.setText("That didn't work!");
            }
        });
// Add the request to the RequestQueue.
        queue.add(stringRequest);
    }


    public void getArrayData() {
        final TextView tvBatteryTest = (TextView)findViewById(R.id.tvBatteryTestStatus);
        tvBatteryTest.setText("thisvalue");

        // Instantiate the RequestQueue.
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "http://192.168.43.234:8000/api/status";

        // Request a string response from the provided URL.
        JsonArrayRequest stringRequest = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        Gson gson = new Gson();
                        ArrayList<TestModel> arrayList = new ArrayList<TestModel>();
                        Type listType = new TypeToken<ArrayList<TestModel>>() { }.getType();
                        ArrayList<TestModel> prods = gson.fromJson(response.toString(), listType);

                        tvBatteryTest.setText("Worked");
                        Log.d("StatusActivity", "Response: " + prods);
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                tvBatteryTest.setText("That didn't work!");
            }
        });
// Add the request to the RequestQueue.
        queue.add(stringRequest);
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
