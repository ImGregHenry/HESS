package com.hess.hessandroid;

import android.app.TabActivity;
import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.app.ActionBar;
import android.os.Bundle;

import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TabHost;
import android.widget.ProgressBar;

public class MainActivity extends AppCompatActivity {

    private TabHost tabHost;
    private Context mContext;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        ImageView imgv = (ImageView)findViewById(R.id.imgHessTitle);
        imgv.setImageResource(R.drawable.icon_hess_clear);

        mContext = getApplicationContext();

        ActionBar actionBar = getSupportActionBar();
        actionBar.hide();

        Button btnPowerUsage = (Button) findViewById(R.id.btnPowerUsage);
        btnPowerUsage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(mContext, UsageActivity.class);
                startActivity(intent);
            }
        });
        Button btnSchedule = (Button) findViewById(R.id.btnSchedule);
        btnSchedule.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(mContext, ScheduleActivity.class);
                startActivity(intent);
            }
        });
        Button btnStatus = (Button) findViewById(R.id.btnStatus);
        btnStatus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(mContext, StatusActivity.class);
                startActivity(intent);
            }
        });
    }
}