<?php

$serverScript = __DIR__ . '/start-simple-http-server.php';

if (DIRECTORY_SEPARATOR == '/') {
    // in linux, use exec to replace parent shell script -- otherwise SIGINT cannot be caught by workerman.
	$serverProc = proc_open("exec php \"{$serverScript}\" start", [STDIN, STDOUT, STDERR], $pipes);
} else {
    // in windows, just start it.
	$serverProc = proc_open("php \"{$serverScript}\"", [STDIN, STDOUT, STDERR], $pipes);
}

if (!is_resource($serverProc)){
    echo "Error: cannot start the server!\n";
    exit(1);
}

sleep(1); // add a simple delay to wait for server started.

$curl = curl_init('http://127.0.0.1:28088');
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_TIMEOUT => 5,
]);
$response = curl_exec($curl);
$expectedResponse = 'Hello world!';
if ($response !== $expectedResponse){
    echo "Error: expect '{$expectedResponse}' but got '$response'. Something is wrong.\n";
	proc_terminate($serverProc, SIGINT); // close the server
   	exit(2);
} else {
    echo "Success.\n";
    proc_terminate($serverProc, SIGINT); // close the server
    exit(0);
}

