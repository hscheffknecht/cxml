<?php

namespace CXml\Models;

use CXml\Models\Responses\ResponseInterface;

class Header
{
    private $senderIdentity;
    private $senderSharedSecret;

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

    public function parse(\SimpleXMLElement $headerXml) : void
    {
        $this->senderIdentity = (string)$headerXml->xpath('Sender/Credential/Identity')[0];
        $this->senderSharedSecret = (string)$headerXml->xpath('Sender/Credential/SharedSecret')[0];
    }

    public function render(\SimpleXMLElement $parentNode) : void
    {
        $headerNode = $parentNode->addChild('Header');

        $this->addNode($headerNode, 'From', 'Unknown');
        $this->addNode($headerNode, 'To', 'Unknown');
        $this->addNode($headerNode, 'Sender', $this->getSenderIdentity() ?? 'Unknown')
            ->addChild('UserAgent', 'Unknown');
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
