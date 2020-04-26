<?php

namespace App\Websocket;

use Ratchet\ConnectionInterface;
use SplObjectStorage;

class ConnectionsStructure
{
    /** @var SplObjectStorage  */
    private $readableConnections;
    /** @var SplObjectStorage  */
    private $editableConnections;
    /** @var  ConnectionInterface */
    private $master;

    public function __construct()
    {
        $this->readableConnections = new SplObjectStorage();
        $this->editableConnections = new SplObjectStorage();
    }

    /**
     * @return ConnectionInterface|null
     */
    public function getMaster(): ?ConnectionInterface
    {
        return $this->master;
    }

    public function addConnection(ConnectionWrapper $connWrapper): bool
    {
        if (
            !$connWrapper->isValid() ||
            $this->readableConnections->contains($connWrapper->getConnection()) ||
            $this->editableConnections->contains($connWrapper->getConnection())
        ) {
            return false;
        }

        if ($connWrapper->getHash()->canEdit()) {
            $this->editableConnections->attach($connWrapper->getConnection());
            if (!$this->master) {
                $this->master = $connWrapper->getConnection();
            }
        } else {
            $this->readableConnections->attach($connWrapper->getConnection());
        }

        return true;
    }

    public function removeConnection(ConnectionWrapper $connWrapper): bool
    {
        if ($this->readableConnections->contains($connWrapper->getConnection())) {
            $this->readableConnections->detach($connWrapper->getConnection());
            return true;
        }
        if ($this->editableConnections->contains($connWrapper->getConnection())) {
            $this->editableConnections->detach($connWrapper->getConnection());
            if (
                $this->master === $connWrapper->getConnection() &&
                $this->editableConnections->count()
            ) {
                $this->editableConnections->rewind();
                $this->master = $this->editableConnections->current();
            }
            return true;
        }

        return false;
    }

    public function sendMessage(ConnectionWrapper $from, string $message): bool
    {
        if (!$this->editableConnections->contains($from->getConnection())) {
            return false;
        }
        /** @var ConnectionInterface $editableConnection */
        foreach ($this->editableConnections as $editableConnection) {
            if ($editableConnection !== $from->getConnection()) {
                $editableConnection->send($message);
            }
        }
        /** @var ConnectionInterface $readableConnection */
        foreach ($this->readableConnections as $readableConnection) {
            $readableConnection->send($message);
        }
        return true;
    }
}