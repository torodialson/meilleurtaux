<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\StatusEnum;
use App\Service\JsonReaderService;

class RateCalculatorController extends AbstractController
{
    private $jsonFileReader;
    
    public function __construct(JsonReaderService $jsonFileReader)
    {
        $this->jsonFileReader = $jsonFileReader;
    }
    
    /**
     * This route is used to test the server.
     * It returns a simple JSON Response with a welcome message.
     *
     * @return JsonResponse
     */
    #[Route('/test', name: 'meilleur_taux', methods: ['GET'])]
    public function test(){
        return $this->json([
            'message' => 'Welcome at Meilleur Taux'
        ]);
    }
    
    /**
     * Returns a list of bank rates based on the given amount and duration.
     * The list is sorted by rate in ascending order.
     * 
     * @param Request $request The request object
     * @return JsonResponse The response
     */
    #[Route('/rate-calculator', name: 'meilleur_taux', methods: ['POST'])]
    public function rateCalculator(Request $request): JsonResponse{
        $dataJSON = json_decode($request->getContent(), true);
        
        // Paths to JSON files
        $bnpFilePath = $this->getParameter('kernel.project_dir') . '/data/BNP.json';
        $carrefourFilePath = $this->getParameter('kernel.project_dir') . '/data/CARREFOURBANK.json';
        $sgFilePath = $this->getParameter('kernel.project_dir') . '/data/SG.json';

        // Reading the files
        $sgData = $this->jsonFileReader->readJsonFile($sgFilePath);
        $carrefourData = $this->jsonFileReader->readJsonFile($carrefourFilePath);
        $bnpData = $this->jsonFileReader->readJsonFile($bnpFilePath);

        // Normalize the data
        $normalizedData = [
            'BNP' => $this->normalizeData($bnpData, 'montant', 'duree'),
            'CarrefourBank' => $this->normalizeData($carrefourData, 'montant_pret', 'duree_pret'),
            'SG' => $this->normalizeData($sgData, 'amount', 'duration'),
        ];
        
        // Extract amount and duration from the request
        $amount = $dataJSON['amount'];
        $duration = $dataJSON['duration'];
    
        // Prepare to filter data based on amount and duration
        $filteredData = [];
        
        foreach ($normalizedData as $bank => $data) {
            foreach ($data as $entry) {
                // Filter based on requested amount and duration
                if ($entry['amount'] == $amount && $entry['duration'] == $duration) {
                    $filteredData[] = [
                        'bank' => $bank,
                        'rate' => $entry['rate'],
                        'amount' => $entry['amount'],
                        'duration' => $entry['duration'],
                    ];
                }
            }
        }
        
        // Sort the results by rate in ascending order
        usort($filteredData, function($a, $b) {
            return $a['rate'] <=> $b['rate'];
        });
    
        return $this->json([
            'status' => StatusEnum::SUCCESS,
            'data' => $filteredData,
        ]);
    }
    
    /**
     * Normalize the given data to contain 'amount', 'duration', and 'rate' keys.
     *
     * The given data is expected to contain the given $amountKey and $durationKey
     * as keys. The 'rate' key is determined by the first of the following keys
     * that is present in the data: 'taux', 'taux_pret', 'rate'.
     *
     * @param array $data The data to normalize
     * @param string $amountKey The key for the amount
     * @param string $durationKey The key for the duration
     * @return array The normalized data
     */
    private function normalizeData(array $data, string $amountKey, string $durationKey): array
    {
        $normalized = [];

        foreach ($data as $item) {
            $normalized[] = [
                'amount' => $item[$amountKey],
                'duration' => $item[$durationKey],
                'rate' => $item['taux'] ?? $item['taux_pret'] ?? $item['rate'], // Handle different rate field names
            ];
        }

        return $normalized;
    }
}