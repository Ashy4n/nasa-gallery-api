<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HolidayController extends AbstractController
{
    #[Route('/holiday', name: 'app_holiday')]
    public function index(): Response
    {
        return $this->render('holiday/index.html.twig', [
            'controller_name' => 'HolidayController',
        ]);
    }
}
