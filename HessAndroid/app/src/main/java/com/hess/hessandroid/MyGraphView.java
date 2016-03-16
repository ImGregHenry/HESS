package com.hess.hessandroid;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.RectF;
import android.util.DisplayMetrics;
import android.view.View;

import com.hess.hessandroid.enums.PeakType;
import com.hess.hessandroid.models.HessSchedule;
import com.hess.hessandroid.models.HessScheduleList;

public class MyGraphView extends View {
    private Paint paint = new Paint(Paint.ANTI_ALIAS_FLAG);
    RectF rectf = new RectF(225, 140, 913, 820);
    private HessScheduleList mScheduleList;
    private Context mContext;

    public MyGraphView(Context context, HessScheduleList scheduleList) {
        super(context);
        mContext = context;
        mScheduleList = scheduleList;
    }

    private final double DEGREES_PER_MIN = 0.25;
    private double convertTimeToStartDegrees(HessSchedule sch) {
        long currentMin = sch.getStartTimeInDateFormat().getMinutes();
        long currentHour = sch.getStartTimeInDateFormat().getHours();
        long totalMins = currentMin + (currentHour * 60);
        return ((double)totalMins) * DEGREES_PER_MIN;
    }

    public void redrawGraph(HessScheduleList schedule) {
        mScheduleList = schedule;
        this.invalidate();
    }

    private double convertTimeToDegreeRange(HessSchedule schedule) {
        long diff = schedule.getEndTimeInDateFormat().getTime() - schedule.getStartTimeInDateFormat().getTime();

        long seconds = diff / (long)1000;
        long minutes = seconds / (long)60;
        double degrees = ((float)minutes) * DEGREES_PER_MIN;
        return degrees;
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);

        //int color = Color.argb(100, r.nextInt(256), r.nextInt(256), r.nextInt(256));
        int color = Color.parseColor("#299c25");
        paint.setColor(color);
        canvas.drawArc(rectf, 0, 360, true, paint);

        for(HessSchedule schedule : mScheduleList.Schedule) {
            double startDegree = convertTimeToStartDegrees(schedule);
            double durationDegree = convertTimeToDegreeRange(schedule);

            if(schedule.PeakTypeID == PeakType.ONPEAK.getID())
                color = getResources().getColor(R.color.on_peak);
            else if (schedule.PeakTypeID == PeakType.OFFPEAK.getID())
                color = getResources().getColor(R.color.off_peak);
            else if (schedule.PeakTypeID == PeakType.MIDPEAKDISABLE.getID())
                color = getResources().getColor(R.color.mid_peak_disable);
            else if (schedule.PeakTypeID == PeakType.MIDPEAKENABLE.getID())
                color = getResources().getColor(R.color.mid_peak_enable);

            paint.setColor(color);

            canvas.drawArc(rectf, ((float)startDegree-90), (float)durationDegree, true, paint);
        }
    }

    public int getRectangleWidthDP() {
        DisplayMetrics dm = getResources().getDisplayMetrics();
        float dp = ((rectf.bottom - rectf.top) * dm.density)  + 0.5f;
        return (int)dp;
    }

    public int getRectangleHeightDP() {
        DisplayMetrics dm = getResources().getDisplayMetrics();
        float dp = ((rectf.right - rectf.left) * dm.density) + 0.5f;
        return (int)dp;
    }
}
