package com.hess.hessandroid;

import android.app.TabActivity;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;

import android.widget.TabHost;
import android.widget.ProgressBar;

public class MainActivity extends TabActivity {

    TabHost tabHost;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        tabHost = getTabHost();

        tabHost = (TabHost)findViewById(R.id.tabHost);

        //Tab 1
        TabHost.TabSpec tab1 = tabHost.newTabSpec("status");
        tab1.setIndicator("Status");
        tab1.setContent(new Intent(this, StatusActivity.class));
        tabHost.addTab(tab1);

        //Tab 2
        TabHost.TabSpec tab2 = tabHost.newTabSpec("usage");
        tab2.setIndicator("Usage");
        tab2.setContent(new Intent(this, UsageActivity.class));
        tabHost.addTab(tab2);

        ProgressBar mProgress = (ProgressBar)findViewById(R.id.remainingPowerProgress);
        mProgress.setProgress(30);

    }
}