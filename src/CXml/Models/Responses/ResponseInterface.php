<?php

namespace CXml\Models\Responses;

interface ResponseInterface
{
    /** Appends the response node to the specified parent node */
    public function render(\SimpleXMLElement $parentNode) : void;
}
