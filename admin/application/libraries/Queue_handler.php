<?php 

// application/libraries/Queue_handler.php

require_once APPPATH.'third_party/php-resque/autoload.php';

class Queue_handler {
    public function __construct() {
        // Initialize the php-resque library
        Resque::setBackend('localhost'); // Redis server address and port
    }

    public function enqueueJob($queueName, $jobClass, $jobData) {
        // Enqueue a job to be processed
        $queueName = 'my_queue_name';
        $jobClass = 'My_Job_Class';
        $jobData = ['data' => 'your_data_here'];
        Resque::enqueue($queueName, $jobClass, $jobData);
    }
}
?>