<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

include_once '../config/database.php';
include_once '../functions/functions.php';
include_once '../objects/comment.php';
 
//initialize database
$database = new Database();
$db = $database->getConnection();

//initialize new comment object
$comment = new Comment($db);

 
// get posted data 
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->film_id) &&
    !empty($data->comment) 
){
    if(strlen($data->comment) < 499)
    {
        // set comment property values
        $comment->film_id = $data->film_id;
        $comment->episode_id = episodeIdFromRId($data->film_id); //fetch episode id of film fromo the movie resource id as provided by SWAPI
        $comment->comment = $data->comment;
        $comment->user_ip = getenv('REMOTE_ADDR'); //fetch current user's ip address
        //$comment->posted_at = date('Y-m-d H:i:s');
    
        // create the comment
        if($comment->create()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Comment was added."));
        }
    
        // if unable to create the comment, inform the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to add comment."));
        }
    }
    else{
            // set response code - 400 bad request
    http_response_code(401);
 
    // tell the user
    echo json_encode(array("message" => "Unable to save comment. Comment is above 500 character limit."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to save comment. Data is incomplete."));
}
?>