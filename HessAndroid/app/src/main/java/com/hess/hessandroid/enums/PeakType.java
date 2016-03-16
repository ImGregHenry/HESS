package com.hess.hessandroid.enums;

/**
 * Created by Greg'sMonster on 11-Mar-16.
 */
public enum PeakType {
    OFFPEAK("OFF", 1),
    ONPEAK("ON", 2),
    MIDPEAKENABLE("MID-ENABLE", 3),
    MIDPEAKDISABLE("MID-DISABLE", 4);

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
