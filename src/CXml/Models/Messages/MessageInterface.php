<?php

namespace CXml\Models\Messages;

interface MessageInterface
{
    /** Appends the message node to the specified parent node */
    public function render(\SimpleXMLElement $parentNode) : void;
}
