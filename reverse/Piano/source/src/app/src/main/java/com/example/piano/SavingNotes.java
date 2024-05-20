package com.example.piano;

import android.os.AsyncTask;
import android.util.Log;
import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.Objects;

public class SavingNotes {

    static ArrayList<String> notes = new ArrayList<>();

    static void AddNotes(int note){
        notes.add(String.valueOf(note));
    }

    static boolean isCowboySound(){
        int sizeNotesStore = notes.size();
        try{
            if((long) notes.size() <= 26){
                return false;
            }
            String[] notesSkip = (notes.stream().skip(sizeNotesStore - 27)).toArray(String[]::new);
            String checkSymbol = Encoder.Encode(notesSkip, 12);

            if(checkSymbol.equals("64|49|184|121|484|627|1011|1635|1321|4275|3457|11187|22624|36604|59224|38329|155044|100345|405904|525411|850131|1375539|1112833|3601203|2913433|9428067|19068664")){
                AsyncTask.execute(() -> {
                    try {
                        sendRequest(String.join("|", notesSkip));
                    } catch (IOException e) {
                        Log.e("RequestException", Objects.requireNonNull(e.getMessage()));
                    }
                });

                notes.clear();
                return true;
            }
        }catch(Exception ignored) {}
        return false;
    }

    public static void sendRequest(String data) throws IOException {
        URL serverEndpoint = new URL(BuildConfig.SERVER_URL);

        HttpURLConnection myConnection = (HttpURLConnection) serverEndpoint.openConnection();
        myConnection.setDoOutput(true);
        myConnection.setRequestMethod("POST");
        myConnection.setRequestProperty("Content-Type", "text");
        myConnection.setRequestProperty("Accept", "text");

        try( DataOutputStream wr = new DataOutputStream( myConnection.getOutputStream())) {
            byte[] input = data.getBytes(StandardCharsets.UTF_8);
            wr.write(input, 0, input.length);
        }

        try(BufferedReader br = new BufferedReader(
                new InputStreamReader(myConnection.getInputStream(), StandardCharsets.UTF_8))) {
            StringBuilder response = new StringBuilder();
            String responseLine;
            while ((responseLine = br.readLine()) != null) {
                response.append(responseLine.trim());
            }
            Log.d("Flag", response.toString());
        }
    }
}
