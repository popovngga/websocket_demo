<?php
namespace App\Http\Controllers\websocket;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;

class PusherSubs implements WampServerInterface, MessageComponentInterface {
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $this->subscribedTopics[$topic->getId()] = $topic;
        echo "New subscribe! ({$topic})\n";
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onEntry($entry) {
        $entryData = json_decode($entry, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['theme'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['theme']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }

    /* The rest of our methods were as they were, omitted from docs to save space */
    /**
     * @inheritDoc
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    /**
     * @inheritDoc
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @inheritDoc
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @inheritDoc
     */
    function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // TODO: Implement onCall() method.
    }

    /**
     * @inheritDoc
     */
    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        // TODO: Implement onUnSubscribe() method.
    }

    /**
     * @inheritDoc
     */
    function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // TODO: Implement onPublish() method.
    }

    /**
     * @inheritDoc
     */
}
