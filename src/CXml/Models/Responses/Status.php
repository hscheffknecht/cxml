<?php

namespace CXml\Models\Responses;

class Status implements ResponseInterface
{
    /** @var int */
    private $statusCode;

    /** @var string */
    private $statusText;

    public function __construct(int $statusCode = 200, string $statusString = 'OK')
    {
        $this->statusCode = $statusCode;
        $this->statusText = $statusString;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusText(): string
    {
        return $this->statusText;
    }

    public function setStatusText(string $statusText): self
    {
        $this->statusText = $statusText;
        return $this;
    }

    /** @noinspection PhpUndefinedFieldInspection */
    public function render(\SimpleXMLElement $parentNode): void
    {
        $node = $parentNode->addChild('Status');

        $node->addAttribute('code', $this->statusCode);
        $node->addAttribute('text', $this->statusText);
    }
}
