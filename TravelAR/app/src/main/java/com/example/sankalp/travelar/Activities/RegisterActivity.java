package com.example.sankalp.travelar.Activities;

import android.app.ActionBar;
import android.app.Activity;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.example.sankalp.travelar.DatabaseHandler;
import com.example.sankalp.travelar.R;

public class RegisterActivity extends Activity {

    ActionBar actionBar;
    DatabaseHandler newDBHandler;
    EditText fullName, userEmail, userPassword, confirmPassword, userCity, twitterHandle;
    Button bRegister;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        actionBar = getActionBar();
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setIcon(
                new ColorDrawable(getResources().getColor(android.R.color.transparent)));
        actionBar.setTitle("SignUp");

        newDBHandler = new DatabaseHandler(this);

        fullName = (EditText) findViewById(R.id.fullName);
        userEmail = (EditText) findViewById(R.id.userEmail);
        userPassword = (EditText) findViewById(R.id.userPassword);
        confirmPassword = (EditText) findViewById(R.id.confirmPassword);
        userCity = (EditText) findViewById(R.id.userCity);
        twitterHandle = (EditText) findViewById(R.id.twitterHandle);

        bRegister = (Button) findViewById(R.id.bRegister);

        bRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (fullName.getText().length() != 0 && userEmail.getText().length() != 0 && userPassword.getText().length() != 0 && confirmPassword.getText().length() != 0 && userCity.getText().length() != 0 && twitterHandle.getText().length() != 0){
                    newDBHandler.registerNewUser(fullName.getText().toString(), userEmail.getText().toString(), userPassword.getText().toString(), userCity.getText().toString(), twitterHandle.getText().toString());
                    Intent i = new Intent(RegisterActivity.this, LoginActivity.class);
                    startActivity(i);
                    finish();
                }
            }
        });

    }

}
