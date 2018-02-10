<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: secondaryUtilities.php
//
// Description: Processes requests from the
// admin page to edit entity relations
// via a private auxiliary web service API.
// ---------------------------------------

	include_once("utilities.php");

	// Function for resetting record relationships in DB when edited via admin UI
	function resetRelations($table, $id, $tags) {
		
		if($table == "business"){ // Edit a business's items
		
			// Parse select2 tag text
			$tagArray = explode("        ", $tags);
			foreach($tagArray as $tag){
				if (!(ctype_space($tag) || $tag == '')) {
					$cleanTagArray[] = trim($tag);
				}
				else
					break;
			}
			$iidList = "";
			
			// Retrieve corresponding tag index list
			if($cleanTagArray){
				$numberOfItems = count($cleanTagArray);
	        	$counter = 1;
				foreach($cleanTagArray as $tag){
					$safeTag = rawurlencode($tag);
					$url = "http://charlesmjenkins.com/reuse/webservice/api.php/item?filter=name,eq,$safeTag&transform=1";
	
					$response = call('GET', $url);
					$jsonObject = json_decode($response,true);
					$iidList .= $jsonObject['item'][0]['id'];
					
					if($counter < $numberOfItems)
		        		$iidList .= ",";
		        		
	        		$counter += 1;
				}
			}
			
			// Call auxiliary web service to delete old relations and replace with new relations
			$url = "http://charlesmjenkins.com/reuse/webservice/aux.php?resetID=$id&newRelations=$iidList&authCode=934mh5Df4X&type=businessItems";
			call('GET', $url);
			
		}
		elseif($table == "item"){ // Edit an item's categories
		
			// Parse select2 tag text
			$tagArray = explode("        ", $tags);
			foreach($tagArray as $tag){
				if (!(ctype_space($tag) || $tag == '')) {
					$cleanTagArray[] = trim($tag);
				}
				else
					break;
			}
			$cidList = "";
			
			// Retrieve corresponding tag index list
			if($cleanTagArray){
				$numberOfItems = count($cleanTagArray);
	        	$counter = 1;
				foreach($cleanTagArray as $tag){
					$safeTag = rawurlencode($tag);
					$url = "http://charlesmjenkins.com/reuse/webservice/api.php/category?filter=name,eq,$safeTag&transform=1";
	
					$response = call('GET', $url);
					$jsonObject = json_decode($response,true);
					$cidList .= $jsonObject['category'][0]['id'];
					
					if($counter < $numberOfItems)
		        		$cidList .= ",";
		        		
	        		$counter += 1;
				}
			}
			
			// Call auxiliary web service to delete old relations and replace with new relations
			$url = "http://charlesmjenkins.com/reuse/webservice/aux.php?resetID=$id&newRelations=$cidList&authCode=934mh5Df4X&type=itemCategories";
			call('GET', $url);
		}
	}
?>