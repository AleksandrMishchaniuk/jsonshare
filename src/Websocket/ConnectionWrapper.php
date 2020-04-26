<?php

namespace App\Websocket;

use App\Entity\Hash;
use App\Entity\Json;
use App\Repository\JsonRepository;
use Ratchet\ConnectionInterface;

class ConnectionWrapper
{
    /** @var  ConnectionInterface */
    private $connection;
    /** @var  JsonRepository */
    private $jsonRepository;
    /** @var  Json */
    private $json = false;
    /** @var  Hash */
    private $hash = false;

    /** @var  ConnectionQueryParams */
    private $queryParams;

    public function __construct(ConnectionInterface $conn, JsonRepository $jsonRepository)
    {
        $this->connection = $conn;
        $this->jsonRepository = $jsonRepository;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->getJson() && $this->getHash();
    }

    /**
     * @return Json|null
     */
    public function getJson(): ?Json
    {
        if ($this->json === false) {
            $this->json = null;
            if ($this->getQueryParams()->getId() && $this->getQueryParams()->getHash()) {
                $this->json = $this->jsonRepository->findByIdAndHash($this->getQueryParams()->getId(), $this->getQueryParams()->getHash());
            }
        }
        return $this->json;
    }

    /**
     * @return Hash|null
     */
    public function getHash(): ?Hash
    {
        if ($this->hash === false) {
            $this->hash = null;
            if (!$this->getQueryParams()->getHash() || !$this->getJson()) {
                return $this->hash;
            }
            foreach ($this->getJson()->getHashes() as $hash) {
                if ($hash->getHash() == $this->getQueryParams()->getHash()) {
                    $this->hash = $hash;
                    break;
                }
            }
        }
        return $this->hash;
    }

    /**
     * @return ConnectionQueryParams
     */
    public function getQueryParams(): ConnectionQueryParams
    {
        if ($this->queryParams === null) {
            $this->queryParams = new ConnectionQueryParams($this->getConnection());
        }
        return $this->queryParams;
    }
}