<?php

namespace App\Models;

use \Rdkafka;

class NotificationProducer
{
    protected $producer;
    protected $topic;
    public function __construct()
    {

        $conf = new RdKafka\Conf();
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');
        $conf->set('metadata.broker.list', 'localhost:9092');

        $this->producer = new RdKafka\Producer($conf);

        $this->topic = $this->producer->newTopic("test");



    }


    public function produce()
    {
        $i = rand(1,10000);

        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, "Messassge $i");

        echo $i;
        $this->producer->poll(0);


    }
}


