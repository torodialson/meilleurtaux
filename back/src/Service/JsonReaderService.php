<?php

namespace App\Service;

class JsonReaderService
{
/**
 * Reads the JSON file from the given file path and returns its contents as an array.
 *
 * This function performs several preprocessing steps on the file contents:
 * - Reads the file and throws an exception if the file cannot be read.
 * - Removes BOM (Byte Order Mark) if present.
 * - Removes any trailing commas from JSON arrays and objects.
 * - Trims whitespace from the JSON content.
 * - Decodes the JSON content into an associative array and checks for errors.
 *
 * @param string $filePath The path to the JSON file.
 * @return array The decoded JSON content as an associative array.
 * @throws \Exception If the file cannot be read or if the JSON decoding fails.
 */
    public function readJsonFile(string $filePath): array
    {
        $fileContents = file_get_contents($filePath);
        if ($fileContents === false) {
            throw new \Exception(sprintf('Failed to read the file: %s', $filePath));
        }
        
        // Check for BOM and remove it if present
        $fileContents = preg_replace('/^\xEF\xBB\xBF/', '', $fileContents);
    
        // Remove trailing commas from JSON arrays and objects
        $fileContents = preg_replace('/,\s*([\]}])/', '$1', $fileContents);

        // Trim whitespace from the content
        $trimmedContents = trim($fileContents);

        $data = json_decode($trimmedContents, true);
        $errorCode = json_last_error();
        if ($errorCode !== JSON_ERROR_NONE) {
            throw new \Exception(sprintf('Failed to decode JSON: %s', json_last_error_msg()));
        }

        return $data;
    }
}