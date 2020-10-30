<?php

namespace CXml\Models\Responses;

class PunchOutSetupResponse implements ResponseInterface
{
    /** @var string */
    private $startPageUrl;

    public function getStartPageUrl(): string
    {
        return $this->startPageUrl;
    }

    public function setStartPageUrl(string $startPageUrl): self
    {
        $this->startPageUrl = $startPageUrl;
        return $this;
    }

    public function render(\SimpleXMLElement $parentNode): void
    {
        $node = $parentNode->addChild('PunchOutSetupResponse');
        $node
            ->addChild('StartPage')
            ->addChild('URL', $this->startPageUrl);
    }
}
