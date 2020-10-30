<?php

namespace CXml\Models;

class CXml
{
    /** @var Header */
    private $header;

    /** @var RequestInterface[] */
    private $requests;

    public function __construct()
    {
        $this->header = new Header();
        $this->requests = [];
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getRequests(): array
    {
        return $this->requests;
    }

    public function setRequests(array $requests): self
    {
        $this->requests = $requests;
        return $this;
    }

    public function addRequest(RequestInterface $request) : self
    {
        $this->requests[] = $request;
        return $this;
    }
}
