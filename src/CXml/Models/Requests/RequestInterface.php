<?php

namespace CXml\Models\Requests;

interface RequestInterface
{
    public function parse(\SimpleXMLElement $requestNode) : void;
}
