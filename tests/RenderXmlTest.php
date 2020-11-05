<?php

use CXml\Models\CXml;
use CXml\Models\Messages\ItemIn;
use CXml\Models\Messages\PunchOutOrderMessage;
use CXml\Models\Messages\PunchOutOrderMessageHeader;
use CXml\Models\Responses\PunchOutSetupResponse;
use CXml\Models\Responses\Status;
use PHPUnit\Framework\TestCase;

class RenderXmlTest extends TestCase
{
    public function testRenderingOfSamplePunchOutSetupResponse() : void
    {
        $cXml = $this->getEnvelope();

        // Status
        $cXml->addResponse(new Status());

        // PunchOutSetupResponse
        $response = new PunchOutSetupResponse();
        $response->setStartPageUrl('https://www.example.com/punchout?sid=76857247543634381');
        $cXml->addResponse($response);

        $resultXml = $this->formatXml($cXml->render());

        self::assertStringEqualsFile(__DIR__ . '/sample-PunchOutSetupResponse.xml', $resultXml);
    }

    public function testRenderingOfSamplePunchOutOrderMessage() : void
    {
        $cXml = $this->getEnvelope();
        $cXml->setHeader(new \CXml\Models\Header());

        // Message
        $message = (new PunchOutOrderMessage())
            ->setBuyerCookie('f5d75ddbc9e75b6346b36ee5c28c5e8b')
            ->setCurrency('EUR')
            ->setLocale('de-DE');
        $cXml->addMessage($message);

        // Message header
        $header = (new PunchOutOrderMessageHeader())
            ->setTotalAmount(271.88)
            ->setShippingCost(0)
            ->setShippingDescription('Unknown')
            ->setTaxSum(21.88)
            ->setTaxDescription('Unknown');
        $message->setHeader($header);

        // Item
        $item = (new ItemIn())
            ->setQuantity(1)
            ->setSupplierPartId('AM2692')
            ->setUnitPrice(250)
            ->setDescription('ANTI-RNase (15-30 U/ul)')
            ->setUnitOfMeasure('EA')
            ->setClassificationDomain('UNSPSC')
            ->setClassification('41106104')
            ->setManufacturerName('Manufacturer X')
            ->setManufacturerPartId('MFPART-1')
            ->setLeadTime(14);
        $message->addItem($item);

        // Render
        $resultXml = $this->formatXml($cXml->render());

        self::assertStringEqualsFile(__DIR__ . '/sample-PunchOutOrderMessage.xml', $resultXml);
    }

    private function getEnvelope(): CXml
    {
        $cXml = new CXml();
        $cXml->setPayloadId('1539050765.83749@example.com');
        $cXml->setTimestamp(new DateTime('2018-04-07T16:16:53-05:00'));
        return $cXml;
    }

    /**
     * @param string $resultXml
     * @return false|string
     */
    private function formatXml(string $resultXml)
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML($resultXml);
        $domDocument->formatOutput = true;
        $resultXml = $domDocument->saveXML();
        return $resultXml;
    }
}
