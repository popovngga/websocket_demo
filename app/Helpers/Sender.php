<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;
use ZMQ;
use ZMQContext;

class Sender
{
    public function __construct(Model $data)
    {
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");
        $socket->send(json_encode($data));
    }
}
