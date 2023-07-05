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
    #[Route('/photo/{id}', name: 'app_image' ,priority: 2)]
    public function getPhoto(Photo $photo, SerializerInterface $serializer): JsonResponse
    {
        $serializedData = $serializer->serialize($photo, 'json',[
            AbstractNormalizer::GROUPS => ['photo:read']
        ]);

        return new JsonResponse($serializedData, 200, [], true);
    }


}
