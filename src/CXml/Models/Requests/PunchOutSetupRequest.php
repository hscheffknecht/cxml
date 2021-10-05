<?php

namespace CXml\Models\Requests;

class PunchOutSetupRequest implements RequestInterface
{
    /** @var string|null */
    private $operation;

    /** @var string|null */
    private $buyerCookie;

    /** @var string|null */
    private $browserFormPostUrl;

    /** @noinspection PhpUndefinedFieldInspection */
    public function parse(\SimpleXMLElement $requestNode): void
    {
        $this->operation = (string)$requestNode->attributes()->operation;
        $this->buyerCookie = $requestNode->xpath('BuyerCookie')[0];
        $this->browserFormPostUrl = $requestNode->xpath('BrowserFormPost/URL')[0];
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(?string $operation): self
    {
        $this->operation = $operation;
        return $this;
    }

    public function getBuyerCookie(): ?string
    {
        return $this->buyerCookie;
    }

    public function setBuyerCookie(?string $buyerCookie): self
    {
        $this->buyerCookie = $buyerCookie;
        return $this;
    }

    public function getBrowserFormPostUrl(): ?string
    {
        return $this->browserFormPostUrl;
    }

    public function setBrowserFormPostUrl(?string $browserFormPostUrl): self
    {
        $this->browserFormPostUrl = $browserFormPostUrl;
        return $this;
    }
}
