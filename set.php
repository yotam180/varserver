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

    if (!isset($d->value)) {
        $responses[] = json_decode("{\"success\": false, \"error\": \"Body val must be set.\"}");
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

    file_put_contents($content_path . "/data/" . $d->id . ".json", json_encode($d->value));
    
    $responses[] = json_decode("{\"success\": true}");
}

echo json_encode($responses);

?>