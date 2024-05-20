package com.example.piano;

import android.annotation.SuppressLint;
import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.RectF;
import android.os.Handler;
import android.os.Message;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.widget.Toast;

import androidx.annotation.NonNull;

import java.util.ArrayList;

public class PianoView extends View {

    public static final int NB = 14;
    private final Paint black, gray, white;
    private final ArrayList<Key> whites = new ArrayList<>();
    private final ArrayList<Key> blacks = new ArrayList<>();
    private int keyWidth, height;
    private final AudioSoundPlayer soundPlayer;

    public PianoView(Context context, AttributeSet attrs) {
        super(context, attrs);
        black = new Paint();
        black.setColor(Color.BLACK);
        white = new Paint();
        white.setColor(Color.WHITE);
        white.setStyle(Paint.Style.FILL);
        gray = new Paint();
        gray.setColor(Color.GRAY);
        gray.setStyle(Paint.Style.FILL);
        soundPlayer = new AudioSoundPlayer(context);
    }

    @Override
    protected void onSizeChanged(int w, int h, int oldw, int oldh) {
        super.onSizeChanged(w, h, oldw, oldh);
        keyWidth = w / NB;
        height = h;
        int count = 15;

        for (int i = 0; i < NB; i++) {
            int left = i * keyWidth;
            int right = left + keyWidth;

            if (i == NB - 1) {
                right = w;
            }

            RectF rect = new RectF(left, 0, right, h);
            whites.add(new Key(rect, i + 1));

            if (i != 0  &&   i != 3  &&  i != 7  &&  i != 10) {
                rect = new RectF((float) (i - 1) * keyWidth + 0.5f * keyWidth + 0.25f * keyWidth, 0,
                        (float) i * keyWidth + 0.25f * keyWidth, 0.67f * height);
                blacks.add(new Key(rect, count));
                count++;
            }
        }
    }

    @Override
    protected void onDraw(@NonNull Canvas canvas) {
        for (Key k : whites) {
            canvas.drawRect(k.rect, k.down ? gray : white);
        }

        for (int i = 1; i < NB; i++) {
            canvas.drawLine(i * keyWidth, 0, i * keyWidth, height, black);
        }

        for (Key k : blacks) {
            canvas.drawRect(k.rect, k.down ? gray : black);
        }
    }

    @SuppressLint("ClickableViewAccessibility")
    @Override
    public boolean onTouchEvent(MotionEvent event) {
        int action = event.getAction();
        boolean isMoveAction = action == MotionEvent.ACTION_MOVE || action == MotionEvent.ACTION_DOWN;
        boolean isUpAction = action == MotionEvent.ACTION_UP;

        for (int touchIndex = 0; touchIndex < event.getPointerCount(); touchIndex++) {
            float x = event.getX(touchIndex);
            float y = event.getY(touchIndex);

            Key k = keyForCoords(x,y);

            if (k != null) {
                k.down = isMoveAction;
                k.up = isUpAction;
            }
        }

        ArrayList<Key> tmp = new ArrayList<>(whites);
        tmp.addAll(blacks);

        for (Key k : tmp) {
            if (k.down) {
                if(!k.isPlaying) {
                    k.isPlaying = true;
                    soundPlayer.playNote(k.sound);
                    invalidate();
                    handler.postDelayed(() -> {
                        k.down = false;
                        k.isPlaying = false;
                        handler.sendEmptyMessage(0);
                    }, 200);
                }
            }
            if(k.up){
                k.up = false;
                SavingNotes.AddNotes(k.sound);
                if(SavingNotes.isCowboySound()){
                    Toast.makeText(this.getContext(), "Поищи флаг в логах", Toast.LENGTH_LONG).show();
                }
            }
        }

        return true;
    }

    private Key keyForCoords(float x, float y) {
        for (Key k : blacks) {
            if (k.rect.contains(x,y)) {
                return k;
            }
        }

        for (Key k : whites) {
            if (k.rect.contains(x,y)) {
                return k;
            }
        }

        return null;
    }


    @SuppressLint("HandlerLeak")
    private Handler handler = new Handler() {
        @Override
        public void handleMessage(@NonNull Message msg) {
            invalidate();
        }
    };
}