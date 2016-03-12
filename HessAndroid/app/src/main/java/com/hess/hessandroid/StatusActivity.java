package com.hess.hessandroid;

import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

import android.widget.ProgressBar;
import android.widget.TextView;
import com.hess.hessandroid.volley.VolleyRequest;
import com.hess.hessandroid.models.BatteryStatusList;
import org.json.JSONObject;

public class StatusActivity extends AppCompatActivity {
    private final static String LOG_STRING = "HESS_STATUS";
    TextView tv;
    TextView remaining;
    ProgressBar progressBar;

    BatteryStatusList list = new BatteryStatusList();


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_status);

        //tv = (TextView) findViewById(R.id.tvTest);
        progressBar = (ProgressBar) findViewById(R.id.remainingPowerProgress);
        remaining = (TextView) findViewById(R.id.remainingTime);

        Log.d(LOG_STRING, "TESTING*************");

        VolleyRequest req = new VolleyRequest();
        req.getBatteryStatusData(this);
        Log.d(LOG_STRING, req.toString());
        //tv.setText("Testing.  Gogogo!");

        //remaining.setText(list.BatteryStatus.toString());

        progressBar.setProgress(70); //TODO: insert PowerLevelPercent from BatteryStatusList
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
