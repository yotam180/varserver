<?php

require("config.php");
$data = json_decode("{}");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("POST only");
}

try {
    $data = json_decode(file_get_contents("php://input"));
}
catch (Exception $e) {
    die("Undecodable POST data");
}

if (!isset($data->id)) {
    die("Body id parameter must be set.");
}

if (!isset($data->password)) {
    die("Body password parameter must be set.");
}

if (file_exists($content_path . "/pass/" . $data->id . ".txt")) {
    die("Password already exists");
} 

file_put_contents($content_path . "/pass/" . $data->id . ".txt", md5($data->password));
die("Ok");

?>