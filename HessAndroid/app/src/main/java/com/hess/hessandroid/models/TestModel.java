package com.hess.hessandroid.models;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

/**
 * Created by Greg on 2015-09-20.
 */
public class TestModel implements Serializable{
    private String title;
    private String test;
    @SerializedName("_id")
    private String id;
    private String enabled;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getTest() {
        return test;
    }

    public void setTest(String test) {
        this.test = test;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getEnabled() {
        return enabled;
    }

    public void setEnabled(String enabled) {
        this.enabled = enabled;
    }
}
