package com.hess.hessandroid;

import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.View;

import com.hess.hessandroid.models.HessSchedule;

public class SetScheduleActivity extends AppCompatActivity {
    private boolean isNewSchedule = true;
    private HessSchedule mSchedule;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_set_schedule);

        isNewSchedule = getIntent().getExtras().getBoolean("IsNew");

        // Update
        if(!isNewSchedule) {
            //send:
            //getIntent().putExtra("MyClass", obj);

            mSchedule = (HessSchedule)getIntent().getSerializableExtra("HessSchedule");
        }



//        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
//        fab.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
//                        .setAction("Action", null).show();
//            }
//        });
    }

}
