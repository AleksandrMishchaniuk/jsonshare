<?php

namespace App\Websocket;


use App\Repository\JsonRepository;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MessageHandler implements MessageComponentInterface
{
    /** @var ConnectionsStructure[]  */
    private $connections;
    /** @var  JsonRepository */
    private $jsonRepository;

    public function __construct(JsonRepository $jsonRepository)
    {
        $this->connections = [];
        $this->jsonRepository = $jsonRepository;
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $wrapper = new ConnectionWrapper($conn, $this->jsonRepository);
        if (!$wrapper->isValid()) {
            return;
        }
        if (!isset($this->connections[$wrapper->getQueryParams()->getId()])) {
            $this->connections[$wrapper->getQueryParams()->getId()] = new ConnectionsStructure();
        }
        $this->connections[$wrapper->getQueryParams()->getId()]->addConnection($wrapper);
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $wrapper = new ConnectionWrapper($conn, $this->jsonRepository);
        if (isset($this->connections[$wrapper->getQueryParams()->getId()])) {
            $this->connections[$wrapper->getQueryParams()->getId()]->removeConnection($wrapper);
        }
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $wrapper = new ConnectionWrapper($conn, $this->jsonRepository);
        if (isset($this->connections[$wrapper->getQueryParams()->getId()])) {
            $this->connections[$wrapper->getQueryParams()->getId()]->removeConnection($wrapper);
        }
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $wrapper = new ConnectionWrapper($from, $this->jsonRepository);
        if (isset($this->connections[$wrapper->getQueryParams()->getId()])) {
            $this->connections[$wrapper->getQueryParams()->getId()]->sendMessage($wrapper, $msg);
        }
    }
}