package com.example.sankalp.travelar;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * Created by Scarecrow on 2/4/2016.
 */
public class DatabaseHandler extends SQLiteOpenHelper {

    private static final String DATABASE_NAME = "TravelAR";
    private static final int DATABASE_VERSION = 1;

    //Table Name
    private static final String TABLE_USERS = "user_details";

    //Table Columns
    private static final String USER_ID = "user_id";
    private static final String USER_FULL_NAME = "full_name";
    private static final String USER_EMAIL = "user_email";
    private static final String USER_PASSWORD = "user_password";
    private static final String USER_CITY = "user_city";
    private static final String USER_TWITTER_HANDLE = "twitter_handle";

    public DatabaseHandler(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String CREATE_CONTACTS_TABLE = "CREATE TABLE " + TABLE_USERS + "("
                + USER_ID + " INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL ," + USER_FULL_NAME + " TEXT NOT NULL," + USER_EMAIL + " TEXT NOT NULL," + USER_PASSWORD + " TEXT NOT NULL," + USER_CITY + " TEXT NOT NULL,"
                + USER_TWITTER_HANDLE + " TEXT NOT NULL" + ")";
        db.execSQL(CREATE_CONTACTS_TABLE);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_USERS);
        // Create tables again
        onCreate(db);
    }

    public int registerNewUser(String fullName, String userEmail, String userPassword, String userCity, String twitterHandle){
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor;
        String query;

        db = this.getReadableDatabase();
        cursor = db.rawQuery("select * from user_details where user_email = ? and user_password = ?",
                new String[]{userEmail, userPassword});

        if (cursor.getCount() == 0) {
            db = this.getWritableDatabase();
            query = "insert into " + TABLE_USERS + " values ( null, " + "\'" + fullName + "\', \'" + userEmail + "\', \'" + userPassword + "\', \'" + userCity + "\', \'" + twitterHandle + "\')";
            db.execSQL(query);
            return 0;
        }else{
            return -1;
        }

    }

    public int userLogin(String userEmail, String userPassword){
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor;

        db = this.getReadableDatabase();
        cursor = db.rawQuery("select * from user_details where user_email = ? and user_password = ?",
                new String[]{userEmail, userPassword});
        if (!cursor.moveToFirst()){
            return -1;
        }
        return cursor.getInt(0);
    }

    public String getTwitterHandle(String userEmail, String userPassword){
        String twitterHandle = null;

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor;

        db = this.getReadableDatabase();
        cursor = db.rawQuery("select twitter_handle from user_details where user_email = ? and user_password = ?",
                new String[]{userEmail, userPassword});
        if (cursor.moveToFirst()){
            do{
                twitterHandle = cursor.getString(0);
            }while(cursor.moveToNext());
        }
        return twitterHandle;
    }

    public String getCity(String userEmail, String userPassword){
        String userCity = null;

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor;

        db = this.getReadableDatabase();
        cursor = db.rawQuery("select user_city from user_details where user_email = ? and user_password = ?",
                new String[]{userEmail, userPassword});
        if (cursor.moveToFirst()){
            do{
                userCity = cursor.getString(0);
            }while(cursor.moveToNext());
        }
        return userCity;
    }

    public void resetDatabase(){
        SQLiteDatabase db = this.getWritableDatabase();
        onUpgrade(db, 1, 1);
    }
}
