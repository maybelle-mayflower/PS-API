<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../functions/functions.php';

//Check is id is specified in URL
if(isset($_GET['id']))
{
    //fetch id and append to url
    $id = $_GET['id'];
    $url = "https://swapi.co/api/films/".$id."/";

    if(isset($_GET['gender'])){
        $gender_desc;
        //switch through filter parameters and return error if incorrect param is specified
        switch($_GET['gender']){
            case "male":
            $gender_desc = "male";
            break;
            case "female":
            $gender_desc = "female";
            break;
            case "hermaphrodite":
            $gender_desc = "hermaphrodite";
            break;
            default:
            echo json_encode(array("Error" => "No gender specified"));
            die();
            break;
        }
        echo searchGender(listCharacters($url, $id), $_GET['gender']);

    }
            //switch through sort parameters and return error if incorrect param is specified

    else if(isset($_GET['sort'])){
        $sort_param;
        switch($_GET['sort']){
            case "gender":
            $sort_param = "gender";
            break;
            case "name":
            $sort_param = "name";
            break;
            case "height":
            $sort_param = "height";
            break;
            default:
            $sort_param = "n/a";
            break;
        }
                //return sort function for ascedning or descending order specified by user. 

        if(isset($_GET['order'])){
            switch($_GET['order']){
                case "asc":
                $sorted_data = sortAscending(listCharacters($url, $id), $sort_param);
                break;
                case "desc":
                $sorted_data = sortDescending(listCharacters($url, $id), $sort_param);
                break;
                default:
                $sorted_data = sortAscending(listCharacters($url, $id), $sort_param);
                break;
                
            }
            echo json_encode($sorted_data);
        }
        else{
            echo json_encode(array("Error" => "Please specifiy sort order as 'asc' or 'desc'"));
        }
    }
    else
    {
        echo listCharacters($url, $id);
    }
}
else{
    //If no id specified fetch all characters in star wars universe
    $url = "https://swapi.co/api/people/";
    echo callAPI($url);

}

//function to fetch characters for specified movie
function listCharacters($url, $id){
    $data = callAPI($url);

    $film = json_decode($data);

    $character_arr = array(
        "title" => $film->title,
        "film_id" => $id
    );
    $character_arr["characters"] = array();
    //loop through characters array in movie object
    foreach($film->characters as $film_char){
        $characters = callAPI($film_char);
        $character_data = json_decode($characters);
        //$char_name = $character_data->name;

        //store name, gender and height in new array
        $char = array(
            "name" => $character_data->name,
            "gender" => $character_data->gender,
            'height' => $character_data->height
        );
        array_push($character_arr["characters"], $char);
    }
    return json_encode($character_arr);
}
