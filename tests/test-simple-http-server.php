<?php

$serverScript = __DIR__ . '/start-simple-http-server.php';


$serverProc = proc_open("php \"{$serverScript}\"", [
    0 => STDIN,
    1 => STDOUT,
    2 => STDERR
], $pipes);

if (!is_resource($serverProc)){
    echo "Error: cannot start the server!\n";
    exit(1);
}

$curl = curl_init('http://127.0.0.1:8088');
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_TIMEOUT => 5,
]);
$response = curl_exec($curl);
$expectedResponse = 'Hello world!';
if ($response !== $expectedResponse){
    echo "Error: expect '{$expectedResponse}' but got '$response'. Something is wrong.\n";
    proc_terminate($serverProc);
    exit(2);
} else {
    echo "Success.\n";
    proc_terminate($serverProc);
    exit(0);
}
