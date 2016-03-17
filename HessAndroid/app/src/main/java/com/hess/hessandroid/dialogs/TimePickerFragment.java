package com.hess.hessandroid.dialogs;

import android.app.Activity;
import android.app.Dialog;
import android.app.DialogFragment;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.text.format.DateFormat;

public class TimePickerFragment extends DialogFragment {
    private TimePickerDialog.OnTimeSetListener mListener;
    private Activity mActivity;
    private int mMin;
    private int mHour;

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        mActivity = activity;

        try {
            mListener = (TimePickerDialog.OnTimeSetListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString() + " must implement OnTimeSetListener");
        }
    }

    public void setTime(int hour, int min) {
        mHour = hour;
        mMin = min;
    }

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        TimePickerDialog tp = new TimePickerDialog(mActivity, mListener, mHour, mMin,
                DateFormat.is24HourFormat(getActivity()));
        return tp;
    }
}
