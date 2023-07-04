<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DateController extends AbstractController
{
    #[Route('/date', name: 'app_date')]
    public function index(): JsonResponse
    {
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/DateController.php',
//        ]);
    }
}
