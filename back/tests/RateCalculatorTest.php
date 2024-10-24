<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RateCalculatorTest extends WebTestCase
{
    public function testRateCalculatorEndpoint()
    {
        // Start a client to simulate a browser
        $client = static::createClient();

        // Make a GET request to the rateCalculator endpoint with query parameters
        $crawler = $client->request('POST', '/rate-calculator?amount=50000&duration=15');

        // Check the response status code
        $this->assertResponseIsSuccessful();

        // Check that the response is JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Decode the JSON response
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Assert that the status is SUCCESS
        $this->assertEquals('SUCCESS', $responseData['status']);

        // Assert that the data contains the expected keys
        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsArray($responseData['data']);

        // Additional checks can be done here
        // For example, check that the data returned has the expected number of results
        // or specific expected rates.
        $this->assertCount(3, $responseData['data']); // Adjust based on your mock data

        // Check that the returned data is sorted by rate
        $this->assertLessThanOrEqual($responseData['data'][1]['rate'], $responseData['data'][2]['rate']);
        $this->assertLessThanOrEqual($responseData['data'][0]['rate'], $responseData['data'][1]['rate']);
    }
}