package com.example.sankalp.travelar.Activities;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;

import com.example.sankalp.travelar.DatabaseHandler;
import com.example.sankalp.travelar.R;
import com.example.sankalp.travelar.Unity.UnityPlayerNativeActivity;

public class MainActivity extends Activity {

    DatabaseHandler newDBHandler;
    ImageView buttonTopDestinations, buttonStartTour;
    String twitterHandle, userCity;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        SharedPreferences pref = getApplicationContext().getSharedPreferences("MyPref", 0); // 0 - for private mode
        twitterHandle = pref.getString("twitter_handle", null);
        userCity = pref.getString("user_city", null);

        buttonTopDestinations = (ImageView) findViewById(R.id.buttonTopDestinations);
        buttonStartTour = (ImageView) findViewById(R.id.buttonStartTour);

        buttonStartTour.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(MainActivity.this, UnityPlayerNativeActivity.class);
                startActivity(i);
            }
        });
        buttonTopDestinations.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(MainActivity.this, TopDestinations.class);
                i.putExtra("twitter_handle", twitterHandle);
                i.putExtra("user_city", userCity);
                startActivity(i);
            }
        });

    }

}
