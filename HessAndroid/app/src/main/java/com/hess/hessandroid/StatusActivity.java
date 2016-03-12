package com.hess.hessandroid;

import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

import android.widget.ProgressBar;
import android.widget.TextView;

import com.hess.hessandroid.models.BatteryStatus;
import com.hess.hessandroid.volley.VolleyRequest;
import org.json.JSONObject;

import java.util.ArrayList;

public class StatusActivity extends AppCompatActivity implements VolleyRequest.VolleyReqCallbackGetBatteryStatus {
    private final static String LOG_STRING = "HESS_STATUS";
    //TextView tv;
    private TextView powerPercentVal;
    private ProgressBar progressBar;
    private Integer powerPercent;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        progressBar = (ProgressBar) findViewById(R.id.remainingPowerProgress);
        powerPercentVal = (TextView) findViewById(R.id.remainingPowerPercent);

        requestBatteryStatus();
    }

    @Override
    public void onVolleyGetBatteryStatusReady(BatteryStatus batteryStatus) {
        initializeListView(batteryStatus);
    }

    private void initializeListView(BatteryStatus batteryStatuses) {
        powerPercent = batteryStatuses.getPowerLevelPercent();
        Log.d(LOG_STRING, powerPercent.toString() + "%");

        powerPercentVal.setText(powerPercent.toString() + "%");
        progressBar.setProgress(powerPercent);
    }

    private void requestBatteryStatus() {
//        Log.d(LOG_STRING, "TESTING*************");

        VolleyRequest req = new VolleyRequest();
        req.getBatteryStatusData(this);
    }



//    @Override
//    public boolean onCreateOptionsMenu(Menu menu) {
//        // Inflate the menu; this adds items to the action bar if it is present.
//        getMenuInflater().inflate(R.menu.menu_status, menu);
//        return true;
//    }
//
//    @Override
//    public boolean onOptionsItemSelected(MenuItem item) {
//        // Handle action bar item clicks here. The action bar will
//        // automatically handle clicks on the Home/Up button, so long
//        // as you specify a parent activity in AndroidManifest.xml.
//        int id = item.getItemId();
//
//        //noinspection SimplifiableIfStatement
//        if (id == R.id.action_settings) {
//            return true;
//        }
//
//        return super.onOptionsItemSelected(item);
//    }
}
