<?php

namespace App\Models;

use \Rdkafka;

class NotificationConsumer
{
    protected $topic;
    public function __construct()
    {
        $conf = new RdKafka\Conf();
        $conf->set('group.id', 'myConsumerGroup');

        $rk = new RdKafka\Consumer($conf);
        $rk->addBrokers("127.0.0.1");

        $topicConf = new RdKafka\TopicConf();

        $topicConf->set('auto.commit.interval.ms', 100);
// Set the offset store method to 'file'
        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('enable.auto.commit', true);

        $topicConf->set('auto.offset.reset', 'earliest');

        $this->topic = $rk->newTopic("test", $topicConf);
        $this->topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

    }

    public function consume()
    {
        while (true) {
            $message = $this->topic->consume(0, 120*10000);
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