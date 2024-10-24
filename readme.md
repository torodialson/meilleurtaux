
---

# Sort Rate Bank

## Project Description

**SortRate Bank** is a web application designed to help users compare and find the best bank rates. The application features a frontend built with modern JavaScript technologies and a backend powered by PHP and Symfony. 

### Features

- Compare different bank rates.
- Sort rates to find the best options available.
- User-friendly interface for easy navigation.

## Project Setup

This project consists of a frontend and a backend. Follow the instructions below to set up and run each part of the application.

### Prerequisites

- **Node.js** (for the frontend)
- **PHP 8** (for the backend)
- **Composer** (for managing PHP dependencies)

### Frontend Setup

1. **Navigate to the Frontend Directory**:
   ```bash
   cd front
   ```

2. **Install Dependencies**:
   ```bash
   npm install
   ```

3. **Run the Development Server**:
   ```bash
   npm run dev
   ```

### Backend Setup

1. **Navigate to the Backend Directory**:
   ```bash
   cd back
   ```

2. **Install Composer Dependencies**:
   If you haven't already, install Composer and run:
   ```bash
   composer install
   ```

3. **Start the Symfony Server**:
   ```bash
   symfony server:start
   ```

### Creating PHPUnit Tests

1. **Navigate to the Tests Directory**:
   - Your test files should be located in the `tests` directory within the backend folder. You can navigate there using:
     ```bash
     cd tests
     ```

2. **Create a New Test File**:
   - Create a new PHP file for your test, for example, `RateCalculatorTest.php`. You can use the following template as a starting point:
   ```php
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
           $this->assertResponseStatusCodeSame(200);

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
   ```

3. **Modify the Test**:
   - Update the test case according to your application's logic and the specific endpoint you want to test.

### Running PHPUnit Tests

1. **Navigate to the Backend Directory** (if not already there):
   ```bash
   cd back
   ```

2. **Run the Tests Using PHPUnit**:
   Execute the following command to run all unit tests:
   ```bash
   vendor/bin/phpunit
   ```

3. **Running a Specific Test File**:
   If you want to run a specific test file, use:
   ```bash
   vendor/bin/phpunit tests/RateCalculatorTest.php
   ```

4. **Viewing Test Results**:
   After running the tests, PHPUnit will provide output indicating which tests passed, failed, or had errors. Check the output for details.

### Additional Notes

- Ensure that your PHP version is set to 8 or higher.
- Make sure that Node.js is installed and updated to the latest version for optimal performance.
- If you encounter any issues with the tests, verify your configuration files (`phpunit.xml.dist`) and ensure that your application logic aligns with the expected test cases.

---