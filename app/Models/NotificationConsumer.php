<?php

namespace App\Models;

use \Rdkafka;

class NotificationConsumer
{
    protected $consumer;
    public function __construct()
    {
        $conf = new RdKafka\Conf();
        $conf->set('group.id', 'myConsumerGroup');
        $conf->set('metadata.broker.list', '127.0.0.1');
        $conf->set('auto.offset.reset', 'earliest');


        $this->consumer = new RdKafka\KafkaConsumer($conf);
        $this->consumer->subscribe(['test']);

    }

    public function consume()
    {
        while (true) {
            echo "Waiting for partition assignment... (make take some time when\n";
            echo "quickly re-joining the group after leaving it.)\n";
            $message = $this->consumer->consume( 12*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    {
                        var_dump("MESSAGE CONSUMED");
                        var_dump($message);
                    }
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }
}