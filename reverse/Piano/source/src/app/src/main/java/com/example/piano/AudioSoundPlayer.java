package com.example.piano;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.res.AssetFileDescriptor;
import android.content.res.AssetManager;
import android.media.AudioAttributes;
import android.media.SoundPool;
import android.util.Log;
import android.util.SparseArray;
import java.util.Objects;

public class AudioSoundPlayer {

    private SoundPool mSoundPool;
    private final AssetManager mAssetManager;
    @SuppressLint("UseSparseArrays")
    private final SparseArray<Integer> SOUND_MAP = new SparseArray<>();
    public AudioSoundPlayer(Context context) {
        createSoundPool();
        mAssetManager = context.getAssets();
        loadAllNote();
    }

    void loadAllNote() {
        // white keys sounds
        SOUND_MAP.put(1, loadNote("noteDo.wav"));
        SOUND_MAP.put(2, loadNote("noteRe.wav"));
        SOUND_MAP.put(3, loadNote("noteMi.wav"));
        SOUND_MAP.put(4, loadNote("noteFa.wav"));
        SOUND_MAP.put(5, loadNote("noteSol.wav"));
        SOUND_MAP.put(6, loadNote("noteLa.wav"));
        SOUND_MAP.put(7, loadNote("noteSi.wav"));
        SOUND_MAP.put(8, loadNote("noteDo2.wav"));
        SOUND_MAP.put(9, loadNote("noteRe2.wav"));
        SOUND_MAP.put(10, loadNote("noteMi2.wav"));
        SOUND_MAP.put(11, loadNote("noteFa2.wav"));
        SOUND_MAP.put(12, loadNote("noteSol2.wav"));
        SOUND_MAP.put(13, loadNote("noteLa2.wav"));
        SOUND_MAP.put(14, loadNote("noteSi2.wav"));
        // black keys sounds
        SOUND_MAP.put(15, loadNote("noteDoSharp.wav"));
        SOUND_MAP.put(16, loadNote("noteReSharp.wav"));
        SOUND_MAP.put(17, loadNote("noteFaSharp.wav"));
        SOUND_MAP.put(18, loadNote("noteSolSharp.wav"));
        SOUND_MAP.put(19, loadNote("noteLaSharp.wav"));
        SOUND_MAP.put(20, loadNote("noteDoSharp2.wav"));
        SOUND_MAP.put(21, loadNote("noteReSharp2.wav"));
        SOUND_MAP.put(22, loadNote("noteFaSharp2.wav"));
        SOUND_MAP.put(23, loadNote("noteSolSharp2.wav"));
        SOUND_MAP.put(24, loadNote("noteLaSharp2.wav"));
    }


    public void playNote(int note) {
        if (note != -1) {
            mSoundPool.play(SOUND_MAP.get(note), 1, 1, 1, 0, 1);
        }
    }

    public int loadNote(String fileName) {
        AssetFileDescriptor afd;
        try {
            afd = mAssetManager.openFd(fileName);
        } catch (Exception e) {
            Log.d("loadNote", Objects.requireNonNull(e.getMessage()));
            return -1;
        }
        return mSoundPool.load(afd, 1);
    }

    private void createSoundPool() {
        AudioAttributes attributes = new AudioAttributes.Builder()
                .setUsage(AudioAttributes.USAGE_GAME)
                .setContentType(AudioAttributes.CONTENT_TYPE_SONIFICATION)
                .build();
        mSoundPool = new SoundPool.Builder()
                .setAudioAttributes(attributes)
                .setMaxStreams(100)
                .build();
    }
}