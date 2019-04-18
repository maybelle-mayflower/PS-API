<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../objects/movies.php';
include_once '../functions/functions.php';
include_once '../objects/comment.php';
include_once '../config/database.php';

//initialize database
$database = new Database();
$db = $database->getConnection();

$comment = new Comment($db);

//$stmt = $comment->getCommentCount();

$movie_arr = array();
$movie_arr["movies"] = array();

//fetch all movies using callAPI function
$data = callAPI("https://swapi.co/api/films/");

$films = json_decode($data);
foreach($films->results as $film_res){
    
    //store required data in record_arr array
    $record_arr = array(
        "title" => $film_res->title,
        "episode_id" => $film_res->episode_id,
        "opening_crawl" => $film_res->opening_crawl,
        "release_date" => $film_res->release_date,
        "comment_count" => $comment->getCommentCount($film_res->episode_id)
        
    );
    array_push($movie_arr["movies"], $record_arr);
}
//functiong to sort returned json data by datetime in chronological order
function dateSortAsc($a, $b){
    $time1 = strtotime($a['release_date']);
    $time2 = strtotime($b['release_date']);
    return $time1 - $time2;
}    
usort($movie_arr['movies'], 'dateSortAsc');

echo json_encode($movie_arr);
