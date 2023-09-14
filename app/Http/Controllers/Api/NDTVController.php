<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NDTVController extends Controller
{
    public function getToken()
    {
        $curl = curl_init();

        $postFields = `apiKey='scnsdnsdklksdlkmlmcksdlksdlksdlksdlksdlksdlks'`;
        // make json string from array
        $postFields = json_encode($postFields);
        $client = Client::create();
        $request = new http\Client\Request;
        $request->setRequestUrl('https://sandbox.mife-aoc.com/api/getAOCToken');
        $request->setRequestMethod('POST');
        $body = new http\Message\Body;
        $body->append(new http\QueryString(array(
            'apiKey' => 'dswdUAW5ZIDZZ7LC',
            'username' => 'B2MYoga',
            'spTransID' => 'B2M123HTJJ2',
            'description' => 'sdacsdc',
            'currency' => 'BDT',
            'amount' => '0.01',
            'onBehalfOf' => 'Apigate_AOC-B2M',
            'purchaseCategoryCode' => 'Game',
            'refundPurchaseCategoryCode' => 'Game',
            'referenceCode' => 'Game',
            'channel' => 'WEB',
            'operator' => 'Robi',
            'taxAmount' => '0.1',
            'callbackURL' => 'https://www.google.com',
            'contactInfo' => 'rayhanalshorif@gmail.com'
        )));
        $request->setBody($body);
        $request->setOptions(array());
        $request->setHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        ));
        $client->enqueue($request)->send();
        $response = $client->getResponse();
        return $this->respondWithSuccess("ok", $postFields);
    }
}
