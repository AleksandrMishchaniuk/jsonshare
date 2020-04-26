<?php

namespace App\Websocket;

use function GuzzleHttp\Psr7\parse_query;
use GuzzleHttp\Psr7\Request;
use Ratchet\ConnectionInterface;

class ConnectionQueryParams
{
    /** @var int */
    private $id;
    /** @var  string */
    private $hash;

    public function __construct(ConnectionInterface $conn)
    {
        /** @var Request $request */
        $request = $conn->httpRequest;
        $requestParams = parse_query($request->getUri()->getQuery());
        $this->id = $requestParams['id'] ?? null;
        $this->id = $this->id ? intval($this->id) : null;
        $this->hash = $requestParams['hash'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }
}