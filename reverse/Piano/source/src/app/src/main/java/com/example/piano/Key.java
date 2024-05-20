package com.example.piano;
import android.graphics.RectF;

public class Key {

    public int sound;
    public RectF rect;
    public boolean down;
    public boolean up;
    public boolean isPlaying;

    public Key(RectF rect, int sound) {
        this.sound = sound;
        this.rect = rect;
    }
}
