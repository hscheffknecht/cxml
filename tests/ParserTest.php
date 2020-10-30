<?php

use CXml\CXmlParser;
use CXml\Models\CXml;
use CXml\Models\Header;
use CXml\Models\Requests\PunchOutSetupRequest;
use CXml\Models\Requests\RequestInterface;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParsingOfSamplePunchOutSetupRequest()
    {
        $parser = new CXmlParser();
        $cXml = $parser->parse(file_get_contents(__DIR__ . '/' . 'sample-PunchOutSetupRequest.xml'));

        // Check result class
        self::assertInstanceOf(CXml::class, $cXml);

        // Check header
        self::assertInstanceOf(Header::class, $cXml->getHeader());
        self::assertSame('buyer', $cXml->getHeader()->getSenderIdentity());
        self::assertSame('jd8je3$ndP', $cXml->getHeader()->getSenderSharedSecret());

        // Check request
        $requests = $cXml->getRequests();
        self::assertIsArray($requests);
        self::assertCount(1, $requests);

        /** @var PunchOutSetupRequest $request */
        $request = current($requests);
        self::assertInstanceOf(RequestInterface::class, $request);
        self::assertInstanceOf(PunchOutSetupRequest::class, $request);
        self::assertSame('550bce3e592023b2e7b015307f965133', $request->getBuyerCookie());
        self::assertSame('https://example.com/cxml_cart', $request->getBrowserFormPostUrl());
    }
}
