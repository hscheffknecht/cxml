<?php

namespace CXml\Models;

use CXml\Models\Responses\ResponseInterface;

class Header
{
    private $senderIdentity;
    private $senderSharedSecret;
    private $from;
    private $to;
    private $userAgent;

    public function getSenderIdentity()
    {
        return $this->senderIdentity;
    }

    public function setSenderIdentity($senderIdentity): self
    {
        $this->senderIdentity = $senderIdentity;
        return $this;
    }

    public function getSenderSharedSecret()
    {
        return $this->senderSharedSecret;
    }

    public function setSenderSharedSecret($senderSharedSecret): self
    {
        $this->senderSharedSecret = $senderSharedSecret;
        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to): self
    {
        $this->to = $to;
        return $this;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function parse(\SimpleXMLElement $headerXml) : void
    {
        $this->senderIdentity = (string)$headerXml->xpath('Sender/Credential/Identity')[0];
        $this->senderSharedSecret = (string)$headerXml->xpath('Sender/Credential/SharedSecret')[0];
    }

    public function render(\SimpleXMLElement $parentNode) : void
    {
        $headerNode = $parentNode->addChild('Header');

        $this->addNode($headerNode, 'From', $this->getFrom() ?? 'Unknown');
        $this->addNode($headerNode, 'To', $this->getTo() ?? 'Unknown');
        $this->addNode($headerNode, 'Sender', $this->getSenderIdentity() ?? 'Unknown')
            ->addChild('UserAgent', $this->getUserAgent() ?? 'Unknown');
    }

    private function addNode(\SimpleXMLElement $parentNode, string $nodeName, string $identity) : \SimpleXMLElement
    {
        $node = $parentNode->addChild($nodeName);

        $credentialNode = $node->addChild('Credential');
        $credentialNode->addAttribute('domain', '');

        $credentialNode->addChild('Identity', $identity);

        return $node;
    }
}
