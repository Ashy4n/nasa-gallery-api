<?php

namespace App\Controller;

use App\Entity\Camera;
use App\Entity\Photo;
use App\Entity\Rover;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PhotoController extends AbstractController
{

//    #[Route('/photo', name: 'app_image')]
//    public function getPhotos(): JsonResponse
//    {
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/ImageController.php',
//        ]);
//    }

    #[Route('/photo/{id}', name: 'app_image' ,priority: 2)]
    public function getPhoto(Photo $photo, SerializerInterface $serializer): JsonResponse
    {
//        $serializedData = $serializer->serialize($photo, 'json',[
//            AbstractNormalizer::GROUPS => ['camera','rover']
//        ]);
//
//        dd($serializedData);
//        return new JsonResponse($serializedData, 200, [], true);
    }


}
