<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// 建立一个到RabbitMQ服务器的连接
// 连接抽象的套接字连接，负责协议版本和身份验证等等。
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 创建一个通道
$channel->queue_declare('hello', false, false, false, false);

// 声明一个队列，然后向队列发布消息
// 队列是幂等的，它仅仅在不存在的情况下被创建。
// 消息内容是一个字节数组。
$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');
echo " [x] Sent 'Hello World!'\n";

// 关闭通道和连接
$channel->close();
$connection->close();

