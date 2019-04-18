<?php

//Error message for incorrect url 
http_response_code(404);

echo json_encode(array("Message " => "Page does not exist"));
?>