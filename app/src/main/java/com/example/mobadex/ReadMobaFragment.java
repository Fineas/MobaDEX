package com.example.mobadex;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import org.json.*;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.mobadex.ui.main.Session;
import com.example.mobadex.ui.main.ShowMoba;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class ReadMobaFragment extends Fragment {

    private Session sess;
    private View resources;
    private Context accessible;
    private ArrayList<View> Mobs; // ARRAY OF MOBS' VIEW
    private List<Integer> mobs_ids; // LIST OF MOBS' ID
    private List<String> mobs_data;

    int do_load_mobs(){

        // ESTABLISH HTTP CONNECTION
        OkHttpClient client = new OkHttpClient();
        String url = "http://192.241.141.11:8001/api.php";

        // PARAMETERS
        RequestBody registration_params = new FormBody.Builder()
                .add("q","hvD6trFjj5PF0sA")
                .add("token",sess.getToken())
                .build();

        // REQUEST STRUCTURE
        Request register_request = new Request.Builder()
                .url(url)
                .post(registration_params)
                .build();

        // PERFORM REQUEST
        client.newCall(register_request).enqueue(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) { // REQUEST FAIL
                Log.d("[MobaDEX]","Error:"+e.toString());
                e.printStackTrace();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException { // REQUEST SUCCEED
                if(response.isSuccessful()){
                    final String myResponse = response.body().string();
                    getActivity().runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Log.d("[MobaDEX]", myResponse);
                            // ERROR RETRIEVING ALL MOBS
                            if(myResponse.equals("Failed to connect to MySQL: ") ||
                                    myResponse.equals("Invalid Token")){
                                Log.d("[MobaDEX]","ERROR!");
                            }
                            else{
                                Log.d("[MobaDEX]","Success!");
                                // THERE ARE NO MOBS YET
                                if(myResponse.equals("No Mobs.")){
                                    Log.d("[MobaDEX]","No Mobs yet.");

                                    // ARRAY TO HOLD ALL MOBS' VIEWS
                                    Mobs = new ArrayList<View>();
                                }
                                // DISPLAY MOBS
                                else{
                                    try {
                                        // JSON INSTANCE TO HOLD REQUEST RESPONSE
                                        JSONObject mobs = new JSONObject(myResponse);
                                        Mobs = new ArrayList<View>();
                                        mobs_ids = new ArrayList<Integer>();
                                        mobs_ids.clear(); // CLEAR MOB IDS LIST IN ORDER TO AVOID DUPLICATES
                                        mobs_data = new ArrayList<String>();
                                        mobs_data.clear();

                                        // ITERATE OVER REQUEST RESPONSE AND RETRIEVE MOBS
                                        Iterator<String> keys = mobs.keys();
                                        while(keys.hasNext()) {
                                            String key = keys.next();

                                            if (mobs.get(key) instanceof JSONObject) {
                                               Log.d("[MobaDEX]","(MOBA)"+mobs.get(key));

                                                final JSONObject tmp_mob = (JSONObject)mobs.get(key); // JSON OBJECT TO HOLD ONE MOBA
                                                // CHECK IF THE MOBA WAS READ ALREADY
                                                if((int)tmp_mob.get("status") == 0){

                                                    // CREATE AN UNREAD VIEW
                                                    View view = LayoutInflater.from(accessible).inflate(R.layout.moba_unread_layout, null);

                                                    TextView username = (TextView)view.findViewById(R.id.moba_name);
                                                    username.setText(tmp_mob.get("name").toString());
                                                    Mobs.add(view);
                                                    mobs_ids.add((int)tmp_mob.get("id"));
                                                    mobs_data.add(tmp_mob.get("data").toString());
                                                }
                                                else {

                                                    // CREATE A READ VIEW
                                                    View view = LayoutInflater.from(accessible).inflate(R.layout.moba_read_layout, null);

                                                    TextView username = (TextView)view.findViewById(R.id.moba_name);
                                                    username.setText(tmp_mob.get("name").toString());
                                                    Mobs.add(view);
                                                    mobs_ids.add((int)tmp_mob.get("id"));
                                                    mobs_data.add(tmp_mob.get("data").toString());
                                                }
                                            }
                                        }
//
                                    }catch (JSONException e) {
                                        e.printStackTrace();
                                    }
                                }
                            }
                            LinearLayout lLayout = (LinearLayout) resources.findViewById(R.id.container_layout);
                            lLayout.removeAllViews();

                            // DISPLAY MOBS
                            if(Mobs.size() > 0){
                                for (View num : Mobs) {

                                    ImageView status = (ImageView)num.findViewById(R.id.moba_status);

                                    // IF MOBA IS UNREAD, ADD ONCLICK LISTENER
                                    if(status.getContentDescription().toString().equals("UNREAD")){

                                        num.setOnClickListener(new View.OnClickListener() {
                                            @Override
                                            public void onClick(View v) {
                                                String moba_data = mobs_data.get(Mobs.indexOf((v))).toString();
                                                Integer moba_id = mobs_ids.get(Mobs.indexOf((v)));
                                                String moba_str_id = String.valueOf(moba_id);

                                                // SHOW MOBA
                                                Intent intent = new Intent(getActivity(), ProcessMoba.class);
                                                intent.putExtra("moba_id",moba_str_id);
                                                intent.putExtra("moba_data",moba_data);
                                                startActivity(intent);
                                            }
                                        });
                                    }
                                    lLayout.addView(num);
                                }
                            }
                        }
                    });
                }
                else{
                    Log.d("[MobaDEX]","Failed!");
                }
            }
        });

        return 0;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // RESOLVE RESOURCES
        resources = inflater.inflate(R.layout.read_moba_layout, container, false);
        accessible = this.getContext();
        return resources;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.d("[MobaDEX]","Reading Mobs...");

        // GET USER SESSION
        sess = Session.getInstance();
    }

    @Override
    public void onResume() {
        super.onResume();

        // RELOAD MOBS
        do_load_mobs();
    }
}
