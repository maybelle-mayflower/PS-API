<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../config/database.php';
include_once '../objects/comment.php';
include_once '../functions/functions.php';

//initialize database
$database = new Database();
$db = $database->getConnection();

//initialize comment
$comment = new Comment($db);

// query products
$stmt = $comment->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // comments array
    $comments_arr=array();
    $comments_arr["records"]=array();
 
    // retrieve table contents

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['id'] to
        // just $id only
        extract($row);
 
        $single_comment=array(
            //"id" => $id,
            //"film_id" => $film_id,
            "film_name" => filmNameFromRId($film_id),
            "comment" => html_entity_decode($comment),
            "user_ip" => $user_ip,
            "posted_at" => $posted_at
        );
 
        array_push($comments_arr["records"], $single_comment);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    echo json_encode($comments_arr);
}
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    echo json_encode(
        array("message" => "No comments found.")
    );
}
 