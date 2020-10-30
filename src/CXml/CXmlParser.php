<?php

namespace CXml;

use CXml\Models\CXml;

class CXmlParser
{
    public function parse(string $xmlContent) : CXml
    {
        // Load XML
        $xml = new \SimpleXMLElement($xmlContent);
        $cXml = new CXml();

        // Header
        $cXml->getHeader()->parse($xml->xpath('Header')[0]);

        // Requests
        $factory = new RequestFactory();
        foreach ($xml->xpath('Request/*') as $requestNode) {
            $request = $factory->create($requestNode->getName());
            $request->parse($requestNode);
            $cXml->addRequest($request);
        }

        return $cXml;
    }
}
