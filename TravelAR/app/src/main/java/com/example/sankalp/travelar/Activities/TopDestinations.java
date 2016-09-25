package com.example.sankalp.travelar.Activities;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListAdapter;
import android.widget.ListView;

import com.example.sankalp.travelar.R;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

public class TopDestinations extends AppCompatActivity {

    ArrayList<String> topCities, cityCodes;
    ListView showDestinations;
    ListAdapter destinationsAdapter;
    String response, query;
    ProgressDialog pDialog;
    String twitterHandle, userCity;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_top_destinations);

        Intent i = getIntent();
        twitterHandle = i.getStringExtra("twitter_handle");
        getSupportActionBar().setSubtitle("Personalised for " + twitterHandle);
        userCity = i.getStringExtra("user_city");

        showDestinations = (ListView) findViewById(R.id.showDestinations);
        topCities = new ArrayList<>();
        cityCodes = new ArrayList<>();

        new GetCities().execute(twitterHandle, userCity);
    }

    private String convertStreamToString(InputStream is){
        String line = "";
        StringBuilder finalString = new StringBuilder();
        BufferedReader reader = new BufferedReader(new InputStreamReader(is));
        try{
            while((line = reader.readLine()) != null){
                finalString.append(line);
            }
        }catch(IOException e){
            e.printStackTrace();
        }
        return finalString.toString();
    }

    private class GetCities extends AsyncTask<String, Void, Void> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(TopDestinations.this);
            pDialog.setMessage("Getting Personalized Destinations..");
            pDialog.setCancelable(false);
            pDialog.show();

        }

        @Override
        protected Void doInBackground(String... arg) {
            String twitter_handle = arg[0];
            String city = arg[1];
            query = "http://139.59.24.207/TravelAR/index.php?type=location&origin="+ city + "&twitter_id=" + twitter_handle;
            URL url = null;
            HttpURLConnection urlConnection = null;
            try{
                url = new URL(query);
                urlConnection = (HttpURLConnection) url.openConnection();
            }catch(IOException e){
                e.printStackTrace();
            }
            try {
                InputStream in = new BufferedInputStream(urlConnection.getInputStream());
                response = convertStreamToString(in);
                Log.d("TD Response", response);
            }catch(IOException e) {
                e.printStackTrace();
            }finally {
                urlConnection.disconnect();
            }
            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            String[] cities = response.split("#");
            int citySize = Math.min(5, cities.length);
            for (int i = 0; i < cities.length - 1; i++){
                topCities.add(cities[i].split(",")[0]);
                cityCodes.add(cities[i].split(",")[1]);
            }
            destinationsAdapter = new ArrayAdapter<>(TopDestinations.this, android.R.layout.simple_list_item_1, topCities);
            showDestinations.setAdapter(destinationsAdapter);
            if (pDialog.isShowing())
                pDialog.dismiss();

            showDestinations.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                    /*Intent i = new Intent(TopDestinations.this, FlightSearch.class);
                    i.putExtra("origin", userCity);
                    i.putExtra("destinationCity", topCities.get(position));
                    i.putExtra("destination", cityCodes.get(position));
                    startActivity(i);*/
                }
            });
        }
    }

}
