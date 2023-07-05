<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PhotoController extends AbstractController
{
    public function __construct(
       private SerializerInterface $serializer
    )
    {
    }

    #[Route('/photos/{id}', name: 'app_photo')]
    public function getPhoto(Photo $photo ): JsonResponse
    {
        $serializedData = $this->serializer->serialize($photo, 'json',[
            AbstractNormalizer::GROUPS => ['photo:read']
        ]);

        return new JsonResponse($serializedData, 200, [], true);
    }
}
