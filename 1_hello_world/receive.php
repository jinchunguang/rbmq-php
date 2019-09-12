<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// 打开一个连接。
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 打开一个通道，声明我们将要消费的队列。
$channel->queue_declare('hello', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

// 定义一个PHP回调将接收服务器发送的消息。消息是从服务器异步发送到客户端的。
$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

// 循环 通道（$channel ）的回调。无论什么时候回调函数（$callback）将传递给接收的消息。
$channel->basic_consume('hello', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
