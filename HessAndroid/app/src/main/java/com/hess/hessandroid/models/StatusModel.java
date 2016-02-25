package com.hess.hessandroid.models;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;
import java.util.List;

/**
 * Created by Greg on 2015-09-20.
 */
public class StatusModel implements Serializable{
    public List<BatteryStatus> batteryStatus;

}
