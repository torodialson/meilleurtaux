<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RateCalculatorTest extends WebTestCase
{
    public function testRateCalculatorEndpoint()
    {
        // Start a client to simulate a browser
        $client = static::createClient();

        // Make a POST request to the rateCalculator endpoint with JSON body
        $client->request('POST', '/rate-calculator', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'amount' => 50000,
            'duration' => 15,
        ]));

        // Assert that the response status code is 200
        $this->assertResponseStatusCodeSame(200); // Check HTTP status code

        // Check that the response is JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Decode the JSON response
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Assert that the status is SUCCESS
        $this->assertEquals('SUCCESS', $responseData['status']);

        // Assert that the data contains the expected keys
        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsArray($responseData['data']);

        // Check that the returned data has the expected number of results
        $this->assertCount(3, $responseData['data']); // Adjust based on your mock data

        // Check that the returned data is sorted by rate
        $this->assertLessThanOrEqual($responseData['data'][1]['rate'], $responseData['data'][2]['rate']);
        $this->assertLessThanOrEqual($responseData['data'][0]['rate'], $responseData['data'][1]['rate']);
    }
}