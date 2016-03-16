package com.hess.hessandroid.enums;

/**
 * Created by Greg'sMonster on 11-Mar-16.
 */
public enum PeakType {
    OFFPEAK("OFF-PEAK", 1),
    ONPEAK("ON-PEAK", 2),
    MIDPEAKENABLE("MID-PEAK-ENABLE", 3),
    MIDPEAKDISABLE("MID-PEAK-DISABLE", 4);

    private String strValue;
    private int intValue;

    PeakType(String toString, int value) {
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
