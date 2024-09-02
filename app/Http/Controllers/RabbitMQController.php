<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQController extends Controller
{
    public function send()
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');

        $message = new AMQPMessage('Hello World Queue');

        $channel = $connection->channel();
        $channel->basic_publish($message, 'pdf_events', 'pdf_create');
        $channel->basic_publish($message, 'pdf_events', 'pdf_log');

        $channel->close();
        $connection->close();

        echo "Message published to RabbitMQ \n";
    }

    public function consumer()
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // Buscar uma mensagem da fila
        $msg = $channel->basic_get('pdf_log_queue');

        if ($msg) {
            Log::info('[x] Received: ', [$msg->getBody()]);
            $channel->basic_ack($msg->getDeliveryTag());
        } else {
            Log::info('No messages in the queue.');
        }

        $channel->close();
        $connection->close();
    }
}
