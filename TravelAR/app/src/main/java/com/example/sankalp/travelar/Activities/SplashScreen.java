package com.example.sankalp.travelar.Activities;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;

import com.example.sankalp.travelar.AppConstants;
import com.example.sankalp.travelar.DatabaseHandler;
import com.example.sankalp.travelar.R;

public class SplashScreen extends Activity {

    DatabaseHandler newDBHandler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash_screen);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                // This method will be executed once the timer is over
                // Start your app main activity
                Intent i = new Intent(SplashScreen.this, LoginActivity.class);
                startActivity(i);
                finish();
            }
        }, AppConstants.SPLASH_TIME_OUT);
    }
}
