package com.hess.hessandroid.enums;

/**
 * Created by Greg'sMonster on 11-Mar-16.
 */
public enum WeekType {
    OFFPEAK("DAY", 1),
    ONPEAK("END", 2);

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
