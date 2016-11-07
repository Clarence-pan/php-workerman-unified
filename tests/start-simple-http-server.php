<?php

use Workerman\Worker;

require(__DIR__.'/../vendor/autoload.php');


$worker = new Worker('http://127.0.0.1:28088');

$worker->count = 1;


$worker->onMessage = function($connection, $data){
    $connection->send("Hello world!");
    $connection->close();
};


Worker::runAll();

