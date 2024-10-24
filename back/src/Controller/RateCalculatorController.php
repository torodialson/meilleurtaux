<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Enum\StatusEnum;

class RateCalculatorController extends AbstractController
{
    #[Route('/test', name: 'meilleur_taux', methods: ['GET'])]
    public function test(){
        return $this->json([
            'message' => 'Welcome at Meilleur Taux'
        ]);
    }
    
    #[Route('/rate-calculator', name: 'meilleur_taux', methods: ['POST'])]
    public function rateCalculator(Request $request): JsonResponse{
        $dataJSON = json_decode($request->getContent(), true);
        return $this->json([
            'status' => StatusEnum::SUCCESS,
            'code' => '403'
        ]);
    }
}