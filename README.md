# cxml
A PHP library to handle cXML punchout orders

## Install

`composer require herbert/cxml:dev-main`

## Usage (punchout workflow)

### 1. Setup page

    // Contains request XML from POST request
    $requestXml = '<?xml…';
    
    // Parse request XML (PunchOutSetupRequest)
    $xmlParser = new CXmlParser();
    $cXmlRequest = $xmlParser->parse($requestXml);

    /** @var PunchOutSetupRequest $setupRequest */
 	$setupRequest = $cXmlRequest->getRequests()[0] ?? null;
 	
 	// Check request
 	if (!$setupRequest || !$setupRequest instanceof PunchOutSetupRequest) {
        throw new Exception('Invalid request');
    }
    
    // Get credentials
    $user = $cXmlRequest->getHeader()->getSenderIdentity();
    $password = $cXmlRequest->getHeader()->getSenderSharedSecret();
    
    // Get punchout data
    $buyerCookie = $setupRequest->getBuyerCookie();
    $postUrl = $setupRequest->getBrowserFormPostUrl();
    
    // Create startPageUrl (store submitted data in your database and generate a login URL with a hash)
    $startPageUrl = $this->generateStartPageUrl($user, $password, $buyerCookie, $postUrl);
    
    // Create cXML envelope and status
    $cXml = $cxml = new CXml();
	$cxml->setPayloadId(time() . '@' . $this->app->getCurrentRequest()->getHost());
    $cXml->addResponse(new Status());

    // Create PunchOutSetupResponse
    $response = new PunchOutSetupResponse();
    $response->setStartPageUrl($startPageUrl);
    $cXml->addResponse($response);
    
    // Return response XML
    return $cXml->render();
    
### 2. Login (startPageUrl)

Read submitted hash (from setup), load needed data and login the user:

    $this->loginByHash($_GET['hash']);

### 3. Cart (return punchout order)

Create a form containing the following XML:

    // XML envelope
    $cXml = new CXml();
    $cXml->setPayloadId(time() . '@' . $hostname);
    $cXml->setHeader(new Header());

    // Message
    $message = (new PunchOutOrderMessage())
        ->setBuyerCookie($buyerCookie)
        ->setCurrency($currency)
        ->setLocale($locale);
    $cXml->addMessage($message);

    // Message header

    $header = (new PunchOutOrderMessageHeader())
        ->setTotalAmount($cart->getTotalAmount())
        ->setShippingCost($cart->getShippingCost())
        ->setShippingDescription('Shipping cost')
        ->setTaxSum($cart->getTaxSum())
        ->setTaxDescription('Tax value');
    $message->setHeader($header);

    // Item
    foreach ($cart->getItems() as $cartItem) {
        $item = (new ItemIn())
            ->setQuantity($cartItem->getAmount())
            ->setSupplierPartId($cartItem->getArticleNumber())
            ->setUnitPrice($cartItem->getUnitPrice())
            ->setDescription($cartItem->getName())
            ->setUnitOfMeasure('EA') // Must be one of UN/CEFACT codes, EA = each
            ->setManufacturerName($cartItem->setManufacturerName())
            ->setManufacturerPartId($cartItem->setManufacturerArticleNumber)
            ->setLeadTime($this->getLeadTime($cartItem->getLeadTime())
            ->setClassificationDomain('EAN')
            ->setClassification($cartItem->getEan());

        $message->addItem($item);
    }

    // Render
    return $cXml->render();
