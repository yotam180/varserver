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

$responses = [];

foreach ($data as $d) {
    if (!isset($d->id)) {
        $responses[] = json_decode("{\"success\": false, \"error\": \"Body ID must be set.\"}");
        continue;
    }

    if (file_exists($content_path . "/pass/" . $d->id . ".txt")) {
        if (!isset($d->password)) {
            $responses[] = json_decode("{\"success\": false, \"error\": \"No password supplied.\"}");
            continue;
        }
        if (file_get_contents($content_path . "/pass/" . $d->id . ".txt") != md5($d->password)) {
            $responses[] = json_decode("{\"success\": false, \"error\": \"Incorrect password.\"}");
            continue;
        }
    }

    if (!file_exists($content_path . "/data/" . $d->id . ".json")) {
        $responses[] = json_decode("{\"success\": false, \"error\": \"Var not found.\"}");
        continue;
    }

    $res = new stdClass();
    $res->success = true;
    $res->content = json_decode(file_get_contents($content_path . "/data/" . $d->id . ".json"));
    
    array_push($responses, $res);
}

echo json_encode($responses);

?>