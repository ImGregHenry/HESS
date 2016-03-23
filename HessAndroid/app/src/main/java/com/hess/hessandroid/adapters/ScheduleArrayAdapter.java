package com.hess.hessandroid.adapters;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.DialogFragment;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TextView;

import com.google.gson.Gson;
import com.hess.hessandroid.R;
import com.hess.hessandroid.SetScheduleActivity;
import com.hess.hessandroid.dialogs.TimePickerFragment;
import com.hess.hessandroid.enums.PeakType;
import com.hess.hessandroid.enums.WeekType;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.volley.VolleyRequest;

import java.util.ArrayList;
import android.support.v7.app.AppCompatActivity;



public class ScheduleArrayAdapter extends ArrayAdapter<HessSchedule> {
    private ArrayList<HessSchedule> mScheduleList;
    private Context mContext;
    private Activity mActivity;

    private TextView tvStartTime;
    private TextView tvEndTime;
    private TextView tvPeak;
    private TextView tvWeek;

    public ScheduleArrayAdapter(Context context, ArrayList<HessSchedule> schedules, Activity activity) {
        super(context, 0, schedules);
        mScheduleList = schedules;
        mContext = context;
        mActivity = activity;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        // Get row item object
        final HessSchedule schedule = getItem(position);

        // Check for existing inflated view
        if (convertView == null) {
            convertView = LayoutInflater.from(getContext()).inflate(R.layout.row_layout_schedule, parent, false);
        }


        // Lookup view for data population
        tvStartTime = (TextView) convertView.findViewById(R.id.tvStartTime);
        tvEndTime = (TextView) convertView.findViewById(R.id.tvEndTime);
        tvPeak = (TextView) convertView.findViewById(R.id.tvPeakType);
        tvWeek = (TextView) convertView.findViewById(R.id.tvWeekType);

        if (position % 2 == 0) {
            convertView.setBackgroundColor(mContext.getResources().getColor(R.color.hess_purple_light));
            tvStartTime.setTextColor(mContext.getResources().getColor(R.color.white));
            tvEndTime.setTextColor(mContext.getResources().getColor(R.color.white));
            tvPeak.setTextColor(mContext.getResources().getColor(R.color.white));
            tvWeek.setTextColor(mContext.getResources().getColor(R.color.white));
        } else {
            convertView.setBackgroundColor(mContext.getResources().getColor(R.color.white));
        }

        ImageView imgClose = (ImageView) convertView.findViewById(R.id.imgBtnClose);
        imgClose.setImageResource(R.drawable.icon_close);
        imgClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new AlertDialog.Builder(mContext)
                        .setIcon(android.R.drawable.ic_dialog_alert)
                        .setTitle("Delete?")
                        .setMessage("Are you sure you want to delete?")
                        .setPositiveButton("YES", new DialogInterface.OnClickListener() {

                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                // Find entry in list and set the deleted flag
                                HessScheduleList list = new HessScheduleList(mScheduleList);
                                list.Schedule.get(position).IsDeleted = true;

                                // Volley request to update the db with deleted entry
                                VolleyRequest vr = new VolleyRequest();
                                vr.postData(mContext, list.toJSONArray());
                            }
                        })
                        .setNegativeButton("NO", null)
                        .show();
            }
        });
        ImageView imgEdit = (ImageView) convertView.findViewById(R.id.imgBtnEdit);
        imgEdit.setImageResource(R.drawable.icon_edit);
        imgEdit.setOnClickListener(
                new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent intent = new Intent(mContext, SetScheduleActivity.class);

                        HessSchedule schedule = mScheduleList.get(position);

                        intent.putExtra("IsNew", false);
                        intent.putExtra("SCHEDULE", schedule);
                        intent.putExtra("SCHEDULELIST", mScheduleList);
                        intent.putExtra("SKIPINDEX", position);
                        mActivity.startActivityForResult(intent, 2);
                    }
                }
        );

        // Populate the data into the template view using the data object
        // Trim the :00 off of the end
        //tvStartTime.setText(schedule.StartTime.substring(0, schedule.StartTime.length()-3));
        //tvEndTime.setText(schedule.EndTime.substring(0, schedule.StartTime.length()-3));
        tvStartTime.setText(schedule.getStartTimeAMPM());
        tvEndTime.setText(schedule.getEndTimeAMPM());
        tvPeak.setText(PeakType.values()[schedule.PeakTypeID-1].toString());
        tvWeek.setText(WeekType.values()[schedule.WeekTypeID-1].toString());

        // Return the completed view to render on screen
        return convertView;
    }
}
