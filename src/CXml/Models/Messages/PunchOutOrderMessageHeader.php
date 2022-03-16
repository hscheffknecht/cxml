<?php


namespace CXml\Models\Messages;


class PunchOutOrderMessageHeader
{
    /** @var float */
    private $totalAmount;

    /** @var float|null */
    private $shippingCost;

    /** @var string */
    private $shippingDescription;

    /** @var float */
    private $taxSum;

    /** @var string */
    private $taxDescription;
    /**
     * @var string
     */
    private $operationAllowed;

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getShippingCost(): ?float
    {
        return $this->shippingCost;
    }

    public function setShippingCost(?float $shippingCost): self
    {
        $this->shippingCost = $shippingCost;
        return $this;
    }

    public function getShippingDescription(): string
    {
        return $this->shippingDescription;
    }

    public function setShippingDescription(string $shippingDescription): self
    {
        $this->shippingDescription = $shippingDescription;
        return $this;
    }

    public function getTaxSum(): float
    {
        return $this->taxSum;
    }

    public function setTaxSum(float $taxSum): self
    {
        $this->taxSum = $taxSum;
        return $this;
    }

    public function getTaxDescription(): string
    {
        return $this->taxDescription;
    }

    public function setTaxDescription(string $taxDescription): self
    {
        $this->taxDescription = $taxDescription;
        return $this;
    }

    public function setOperationAllowed(string $operation): self
    {
        $this->operationAllowed = $operation;
        return $this;
    }

    public function render(\SimpleXMLElement $parentNode, string $currency, string $locale): void
    {
        $node = $parentNode->addChild('PunchOutOrderMessageHeader');
        $node->addAttribute('operationAllowed', $this->operationAllowed);

        // Total
        $this->addPriceNode($node, 'Total', $currency, $this->totalAmount);

        // Shipping
        $this->addPriceNode($node, 'Shipping', $currency, $this->shippingCost, $this->shippingDescription, $locale);

        // Tax
        $this->addPriceNode($node, 'Tax', $currency, $this->taxSum, $this->taxDescription, $locale);
    }

    private function formatPrice(float $totalAmount)
    {
        return number_format($totalAmount, 2, '.', '');
    }

    private function addPriceNode(\SimpleXMLElement $parentNode, string $name, string $currency, float $priceValue, string $description = null, string $locale = null)
    {
        $node = $parentNode->addChild($name);

        $node
            ->addChild('Money', $this->formatPrice($priceValue))
            ->addAttribute('currency', $currency);

        if ($description !== null) {
            $node->addChild('Description', $description)
                ->addAttribute('xml:xml:lang', $locale);
        }
    }
}