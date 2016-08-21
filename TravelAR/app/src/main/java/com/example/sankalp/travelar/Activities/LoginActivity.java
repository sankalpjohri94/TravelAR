package com.example.sankalp.travelar.Activities;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.MotionEvent;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.example.sankalp.travelar.DatabaseHandler;
import com.example.sankalp.travelar.R;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class LoginActivity extends Activity {

    DatabaseHandler newDBHandler;
    EditText inputEmail, inputPassword;
    TextView errorText;
    Button buttonLogin;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        inputEmail = (EditText) findViewById(R.id.inputEmail);
        inputPassword = (EditText) findViewById(R.id.inputPassword);
        buttonLogin = (Button) findViewById(R.id.buttonLogin);
        errorText = (TextView) findViewById(R.id.errorText);

        newDBHandler = new DatabaseHandler(this);

        errorText.setVisibility(View.GONE);

        inputEmail.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                errorText.setVisibility(View.GONE);
                return false;
            }
        });

        buttonLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (!isValidEmailAddress(inputEmail.getText().toString())) {
                    errorText.setText("Enter an Email ID");
                    errorText.setVisibility(View.VISIBLE);
                } else {
                    if (inputPassword.getText().toString().length() == 0) {
                        Intent i = new Intent(LoginActivity.this, RegisterActivity.class);
                        startActivity(i);
                    } else {
                        int result = newDBHandler.userLogin(inputEmail.getText().toString(), inputPassword.getText().toString());
                        if (result == -1) {
                            errorText.setText("Wrong Email ID or Password");
                            errorText.setVisibility(View.VISIBLE);
                        } else {
                            String twitterHandle = newDBHandler.getTwitterHandle(inputEmail.getText().toString(), inputPassword.getText().toString());
                            String userCity = newDBHandler.getCity(inputEmail.getText().toString(), inputPassword.getText().toString());
                            Intent i = new Intent(LoginActivity.this, MainActivity.class);
                            SharedPreferences pref = getApplicationContext().getSharedPreferences("MyPref", 0); // 0 - for private mode
                            SharedPreferences.Editor editor = pref.edit();
                            editor.putString("twitter_handle", twitterHandle);
                            editor.putString("user_city", userCity);
                            editor.commit();
                            startActivity(i);
                            finish();
                        }
                    }
                }
            }
        });
    }

    public static boolean isValidEmailAddress(String email) {
        boolean result = true;
        Pattern pattern;
        Matcher matcher;
        final String EMAIL_PATTERN = "^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$";
        pattern = Pattern.compile(EMAIL_PATTERN);
        matcher = pattern.matcher(email);
        return matcher.matches();
    }
}
