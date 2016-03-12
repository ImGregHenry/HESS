package com.hess.hessandroid.enums;

/**
 * Created by Greg'sMonster on 11-Mar-16.
 */
public enum WeekType {
    OFFPEAK("WEEKDAY", 1),
    ONPEAK("WEEKEND", 2);

    private String strValue;
    private int intValue;

    WeekType(String toString, int value) {
        strValue = toString;
        intValue = value;
    }

    @Override
    public String toString() {
        return strValue;
    }

    public int getID() {
        return intValue;
    }
}
