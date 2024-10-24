<?php

namespace App\Service;

class JsonReaderService
{
    public function readJsonFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: " . $filePath);
        }

        $jsonContents = file_get_contents($filePath);
        $data = json_decode($jsonContents, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON: " . json_last_error_msg());
        }

        return $data;
    }
}