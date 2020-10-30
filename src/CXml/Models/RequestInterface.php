<?php

namespace CXml\Models;

interface RequestInterface
{
    public function parse(\SimpleXMLElement $requestNode) : void;
}
