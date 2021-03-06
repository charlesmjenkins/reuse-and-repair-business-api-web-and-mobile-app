// CS419 - Reuse & Repair Mobile App
// ---------------------------------------
// Charles Jenkins
//
// Billy Kerns
//
// Eric Cruz
//
// Title: ItemActivity.java
//
// Description: Activity to display items
// related to the category that the user
// selected
// ---------------------------------------

package com.example.eric.reuserepair.app;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v4.app.Fragment;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Iterator;
import java.util.List;
import java.util.concurrent.ExecutionException;

public class ItemActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        setContentView(R.layout.activity_item);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(this.getIntent().getExtras().getString("category"));
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    /**
     * A fragment to display the items related to the category.
     */
    public static class ItemFragment extends Fragment {
        ArrayAdapter<String> mItemAdapter;
        public ItemFragment() {
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {

            String selectedCat = getActivity().getIntent().getExtras().getString("category");
            String allItemsString = null;
            String allCategoriesString = null;
            String allItemCategoriesString = null;
            JSONArray itemsArray = null;
            JSONArray categoryArray = null;
            JSONArray itemsCategoriesArray = null;

            GetItem allItems = new GetItem();
            GetCategory allCategories = new GetCategory();
            GetItemCategory allItemCategories = new GetItemCategory();


            String CID = null;
            ArrayList<String> selectedItemNumbers = new ArrayList<String>();
            ArrayList<String> data = new ArrayList<String>();
            //Get JSON for item, category, and item-category table
            try {
                allItemsString = allItems.execute().get();
                JSONObject itemsJSONObj = new JSONObject(allItemsString);
                itemsJSONObj = new JSONObject(itemsJSONObj.get("item").toString());
                itemsArray = itemsJSONObj.getJSONArray("records");


                allCategoriesString = allCategories.execute().get();
                JSONObject categoriesJSONObj = new JSONObject(allCategoriesString);
                categoriesJSONObj = new JSONObject(categoriesJSONObj.get("category").toString());
                categoryArray = categoriesJSONObj.getJSONArray("records");

                allItemCategoriesString = allItemCategories.execute().get();
                JSONObject ItemsCategoriesJSONObj = new JSONObject(allItemCategoriesString);
                ItemsCategoriesJSONObj = new JSONObject(ItemsCategoriesJSONObj.get("item-category").toString());
                itemsCategoriesArray = ItemsCategoriesJSONObj.getJSONArray("records");

            } catch (InterruptedException e) {
                e.printStackTrace();
            } catch (ExecutionException e) {
                e.printStackTrace();
            } catch (JSONException e) {
                e.printStackTrace();
            }
            //Go through categories until selected category is found.
            //Get the id of this category
            for(int i = 0; i < categoryArray.length(); i++){
                try {
                    JSONArray lookingForSelected = categoryArray.getJSONArray(i);
                    String key = lookingForSelected.getString(1);
                    if(key.equals(selectedCat)){
                        CID = lookingForSelected.getString(0);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
            //Go through item-categories looking for the found
            //category id.
            //Add item iid of matches
            for(int i = 0; i < itemsCategoriesArray.length(); i++){
                try {
                    JSONArray lookingForCID = itemsCategoriesArray.getJSONArray(i);
                    String key = lookingForCID.getString(1);
                    if(key.equals(CID)){
                        selectedItemNumbers.add(lookingForCID.getString(0));
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
            //Go through item looking for id s that
            //match the selected iid s.
            //If found add item name to array
            for(int i = 0; i < itemsArray.length(); i++){
                try {
                    JSONArray lookingForIID = itemsArray.getJSONArray(i);
                    String key = lookingForIID.getString(0);
                    if(selectedItemNumbers.contains(key)){
                        data.add(lookingForIID.getString(1));
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            // Create an ArrayAdapter for the blank category list to populate ListView
            mItemAdapter =
                    new ArrayAdapter<String>(
                            getActivity(),
                            R.layout.list_item_item,
                            R.id.list_item_item_textview,
                            data
                    );
            View rootView = inflater.inflate(R.layout.fragment_item, container, false);

            ListView listView = (ListView) rootView.findViewById(R.id.listview_item);
            listView.setAdapter(mItemAdapter);
            final String finalAllItemsString = allItemsString;

            // On-click listener to open BusinessActivity
            listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {

                @Override
                public void onItemClick(AdapterView<?> adapterView, View view, int position, long l) {
                    String item = mItemAdapter.getItem(position);
                    Intent intent = new Intent(getActivity(), BusinessActivity.class);
                    intent.putExtra("allItems", finalAllItemsString);
                    intent.putExtra("selectedItem", item);
                    startActivity(intent);
                }
            });
            return rootView;
        }
    }
}
