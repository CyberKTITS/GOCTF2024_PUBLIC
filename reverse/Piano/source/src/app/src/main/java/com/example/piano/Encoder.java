package com.example.piano;

import android.util.Log;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.stream.Stream;

public class Encoder {
    public static String Encode(String[] numArray, long guid) {
        String[] outputValue = new String[numArray.length];
        long fib = guid;
        long old = 0;
        long fibOld;
        for (int i = 0; i < numArray.length; i++) {
            fibOld = fib;
            fib = old + fib;
            int item = Integer.parseInt(numArray[i]);
            outputValue[i] = String.valueOf((item + fib) + (item * fib));
            old = fibOld;
        }
        return String.join("|", outputValue);
    }
}