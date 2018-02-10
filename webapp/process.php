<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: process.php
//
// Description: Processes the inline data 
// editing AJAX requests from admin.php.
// ---------------------------------------
// Acknowledgement:
// http://stackoverflow.com/questions/4417690/return-errors-from-php-run-via-ajax

    include_once("utilities.php");
    include("secondaryUtilities.php");
    
    if(isset($_GET['id']))
    {
        // Retrieve updated data
        $id = $_GET['id'];
        $data = $_GET['data'];
        $key = $_GET['key'];
        $table = $_GET['table'];
        
        if($key == "itemList"){ // Reset relations when editing data relationships
            $tags = $_GET['itemsList'];
            
            resetRelations($table, $id, $tags);
        }
        else{ 
            if($data == ""){
                $dataString = $key."__is_null";
                
                if($key == "name") // Tried to submit record without a name; return an error
                {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
                }
            }
            else{ // Send PUT request to update regular data via web service
                $dataString = "$key=$data";
                
                // If address changed, reset latitude and longitude in database
                if($key == "address"){
                    $latLong= call('GET', "https://maps.googleapis.com/maps/api/geocode/json?address=".rawurlencode($data)."&key="); //Insert API key here
		
            		$jsonObject = json_decode($latLong,true);
            	    $dataString .= "&lat=".$jsonObject['results'][0]['geometry']['location']['lat'];
            	    $dataString .= "&long=".$jsonObject['results'][0]['geometry']['location']['lng'];
                }
            }
      
            if(call('PUT',"http://charlesmjenkins.com/reuse/webservice/api.php/$table/$id?appcert=umf349MFM9m3m9HDH23MhKNP800m0MFqj0a2", $dataString))
            	echo 'success';
        }
    }
?>