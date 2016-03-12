package com.hess.hessandroid.adapters;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.TextView;

import com.hess.hessandroid.R;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;
import com.hess.hessandroid.volley.VolleyRequest;

import java.util.ArrayList;

/**
 * Created by Greg'sMonster on 12-Mar-16.
 */
public class ScheduleArrayAdapter extends ArrayAdapter<HessSchedule> {
    private ArrayList<HessSchedule> mScheduleList;
    private Context mContext;
    public ScheduleArrayAdapter(Context context, ArrayList<HessSchedule> schedules) {
        super(context, 0, schedules);
        mScheduleList = schedules;
        mContext = context;
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
        TextView tvStartTime = (TextView) convertView.findViewById(R.id.tvStartTime);
        TextView tvEndTime = (TextView) convertView.findViewById(R.id.tvEndTime);
        TextView tvPeak = (TextView) convertView.findViewById(R.id.tvPeakType);
        TextView tvWeek = (TextView) convertView.findViewById(R.id.tvWeekType);
        Button btnDelete = (Button) convertView.findViewById(R.id.btnDeleteScheduleRow);
        btnDelete.setOnClickListener(new View.OnClickListener() {
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

        // Populate the data into the template view using the data object
        tvStartTime.setText(schedule.StartTime);
        tvEndTime.setText(schedule.EndTime);
        tvPeak.setText(String.valueOf(schedule.PeakTypeID));
        tvWeek.setText(String.valueOf(schedule.WeekTypeID));

        // Return the completed view to render on screen
        return convertView;
    }
}
