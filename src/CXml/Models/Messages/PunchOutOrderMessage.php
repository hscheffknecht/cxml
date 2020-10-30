<?php

namespace CXml\Models\Messages;

class PunchOutOrderMessage implements MessageInterface
{
    /** @var string */
    private $buyerCookie;

    /** @var PunchOutOrderMessageHeader */
    private $header;

    /** @var ItemIn[] */
    private $items = [];

    /** @var string */
    private $currency = 'USD';

    /** @var string string */
    private $locale = 'en-US';

    public function getBuyerCookie(): string
    {
        return $this->buyerCookie;
    }

    public function setBuyerCookie(string $buyerCookie): self
    {
        $this->buyerCookie = $buyerCookie;
        return $this;
    }

    public function getHeader(): PunchOutOrderMessageHeader
    {
        return $this->header;
    }

    public function setHeader(PunchOutOrderMessageHeader $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function addItem(ItemIn $item) : self
    {
        $this->items[] = $item;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function render(\SimpleXMLElement $parentNode): void
    {
        $node = $parentNode->addChild('PunchOutOrderMessage');
        $node->addChild('BuyerCookie', $this->buyerCookie);

        if ($this->header) {
            $this->header->render($node, $this->currency, $this->locale);
        }

        foreach ($this->items as $item) {
            $item->render($node, $this->currency, $this->locale);
        }
    }
}
