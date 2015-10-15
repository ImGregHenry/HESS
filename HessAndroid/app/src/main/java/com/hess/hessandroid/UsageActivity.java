package com.hess.hessandroid;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

public class UsageActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_usage);
        initialize();
    }

    public void initialize() {
        TextView tvHeaderHome = (TextView)findViewById(R.id.tvHeaderMyHome);
        tvHeaderHome.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                Log.d("StatusActivity", "Starting MyHome activity.");
                Intent intent = new Intent(UsageActivity.this, MyHomeActivity.class);
                startActivity(intent);
            }
        });

        TextView tvStatus = (TextView)findViewById(R.id.tvHeaderStatus);
        tvStatus.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                Log.d("StatusActivity", "Starting Status activity.");
                Intent intent = new Intent(UsageActivity.this, StatusActivity.class);
                startActivity(intent);
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_usage, menu);
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
