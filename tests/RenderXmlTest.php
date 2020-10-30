<?php

use CXml\Models\CXml;
use CXml\Models\Responses\PunchOutSetupResponse;
use CXml\Models\Responses\Status;
use PHPUnit\Framework\TestCase;

class RenderXmlTest extends TestCase
{
    public function testRenderingOfSamplePunchOutSetupResponse()
    {
        // Envelope
        $cXml = new CXml();
        $cXml->setPayloadId('1539050765.83749@example.com');
        $cXml->setTimestamp(new DateTime('2018-04-07T16:16:53-05:00'));

        // Status
        $cXml->addResponse(new Status());

        // PunchOutSetupResponse
        $response = new PunchOutSetupResponse();
        $response->setStartPageUrl('https://www.example.com/punchout?sid=76857247543634381');
        $cXml->addResponse($response);

        $resultXml = $cXml->render();

        self::assertStringEqualsFile(__DIR__ . '/sample-PunchOutSetupResponse.xml', $resultXml);
    }
}
