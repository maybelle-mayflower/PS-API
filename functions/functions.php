<?php
function callAPI( $url){
    //initial cURL
    $curl = curl_init();
    //Simple GET Call
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'Curl Req'
    ]);
    // Send GET request & save response to $resp
    $resp = curl_exec($curl);
    // Close request 
    curl_close($curl);
    
    return $resp;
 }

 function cmToFeet($height_cm){
    return $height_cm/30.48;

}

 //function to search character list for keywords and return json data including height information
 function searchGender($json_data, $keyword){
     $filtered_array = array();
     $item_height = 0;

      $json = json_decode($json_data);
      foreach($json->characters as $item)
      {
        if(strcasecmp($item->gender, $keyword) == 0)
        {
            $char = array(
                "name" => $item->name,
                "gender" => $item->gender,
                'height' => $item->height
            );
            $item_height += intval($item->height);
            array_push($filtered_array, $char);
        }
      }
      echo json_encode(array("characters" => $filtered_array, "Total Height" => array("cm" => $item_height, "feet" => ($item_height/30.48) )));
 }

//function to sort character list in ascending order
 function sortAscending($account_json, $key)
 {
     $accounts = json_decode($account_json, true);

     $ascending = function($accountA, $accountB) use ($key) {
        if(strcasecmp($key, 'height') == 0){
           return $accountA[$key]-$accountB[$key];
        }
        else{
         return strcmp($accountA[$key], $accountB[$key]);
        }
 
     };
     usort($accounts["characters"], $ascending);
 
     return $accounts;
 }

 //function to sort character list in descending order

 function sortDescending($account_json, $key)
{
    $accounts = json_decode($account_json, true);

    $descending = function($accountA, $accountB) use ($key) {
        if(strcasecmp($key, 'height') == 0){
            return $accountB[$key]-$accountA[$key];
         }
         else{
          return strcmp($accountB[$key], $accountA[$key]);
         }
    };
    usort($accounts["characters"], $descending);

    return $accounts;
}

//fetch film name from the resource id as provided by SWAPI
 function filmNameFromRId($id){
    $data = callAPI("https://swapi.co/api/films/".$id."/");
    $films = json_decode($data);
    return $films->title;

}
//fetch episode id from the film resource id as provided by SWAPI

function episodeIdFromRId($id){
    $data = callAPI("https://swapi.co/api/films/".$id."/");
    $films = json_decode($data);
    return $films->episode_id;
}


?>