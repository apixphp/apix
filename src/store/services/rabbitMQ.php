<?php
/*
 * This file is client and http request of the queue service.
 *
 * client and rabbitMQ queue
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace src\store\services;
use lib\utils;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use src\app\mobi\v1\model\sudb\test;
use src\store\services\httprequest as request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


/**
 * Represents a queue class.
 *
 * main call
 * return type string
 */

class rabbitMQ {

    private $connection;
    private $channel;
    private $app;
    private $declare;

    /**
     * queue construct class.
     *
     */
    public function __construct($app=null,$declare=null){

        //rabbitMQ connections
        if($app!==null){
            $projectConfig="\\src\\app\\".$app."\\".utils::getAppVersion($app)."\\config\\rabbitMQ";
            $this->app=$app;
            $this->declare=$declare;
        }
        else{
            $projectConfig="\\src\\app\\".app."\\".version."\\config\\rabbitMQ";
            $this->app=app;
            $this->declare=app;
        }

        $rabbitMQ=$projectConfig::rmqSettings();
        $this->connection=new AMQPStreamConnection($rabbitMQ['rabbitMQ']['host'], $rabbitMQ['rabbitMQ']['port'], $rabbitMQ['rabbitMQ']['user'], $rabbitMQ['rabbitMQ']['password']);
        $this->channel=$this->connection->channel();

    }



    public function publisher(){

        $this->channel->exchange_declare($this->declare, 'fanout', false, false, false);

        $msg = new AMQPMessage(test::create(['pusher'=>1]));

        $this->channel->basic_publish($msg, $this->declare);

        $this->channel->close();
        $this->connection->close();
    }


    public function subscriber(){

        $this->channel->exchange_declare($this->declare, 'fanout', false, false, false);

        list($queue_name, ,) = $this->channel->queue_declare("", false, false, true, false);

        $this->channel->queue_bind($queue_name, $this->declare);

        $callback = function($msg){};

        $this->channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while(count($this->channel->callbacks)) {

            $process = new Process('php api job pusher rabbitmq mobi user publisher');
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            sleep(5);
        }

        $this->channel->close();
        $this->connection->close();
    }


}
