<?php

namespace CXml\Models;

use CXml\Models\Messages\MessageInterface;
use CXml\Models\Requests\RequestInterface;
use CXml\Models\Responses\ResponseInterface;

class CXml
{
    /** @var Header */
    private $header;

    /** @var RequestInterface[] */
    private $requests = [];

    /** @var MessageInterface[] */
    private $messages = [];

    /** @var ResponseInterface[]; */
    private $responses = [];

    /** @var string */
    private $payloadId;

    /** @var \DateTime */
    private $timestamp;

    public function __construct()
    {
        $this->timestamp = new \DateTime();
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function setHeader(Header $header): self
    {
        $this->header = $header;
        return $this;
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

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    public function addMessage(MessageInterface $message) : self
    {
        $this->messages[] = $message;
        return $this;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function setResponses(array $responses): self
    {
        $this->responses = $responses;
        return $this;
    }

    public function addResponse(ResponseInterface $response)
    {
        $this->responses[] = $response;
    }

    /** @noinspection PhpUndefinedFieldInspection */
    public function render() : string
    {
        $xmlData = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE cXML SYSTEM "http://xml.cXML.org/schemas/cXML/1.2.040/cXML.dtd">
<cXML payloadID="" timestamp="" />
XML;

        // Envelope
        $xml = new \SimpleXMLElement($xmlData);
        $xml->attributes()->payloadID = $this->payloadId;
        $xml->attributes()->timestamp = $this->timestamp->format('c');

        // Messages
        if (!empty($this->messages)) {
            $messageChild = $xml->addChild('Message');

            foreach ($this->messages as $message) {
                $message->render($messageChild);
            }
        }

        // Responses
        if (!empty($this->responses)) {
            $responseChild = $xml->addChild('Response');

            foreach ($this->responses as $response) {
                $response->render($responseChild);
            }
        }

        return $xml->asXML();
    }

    public function createPayloadId() : string
    {
        return sprintf(
            '%d.%d@%s',
            time(),
            rand(0, 9999),
            gethostname()
        );
    }

    public function getPayloadId(): string
    {
        return $this->payloadId;
    }

    public function setPayloadId(string $payloadId): self
    {
        $this->payloadId = $payloadId;
        return $this;
    }

    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}
