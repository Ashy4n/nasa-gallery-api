<?php

namespace App\Controller;

use App\DTO\HolidayDTO;
use App\DTO\HolidayParamsInput;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PhotosController extends AbstractController
{
    public function __construct(
        private PhotoRepository     $photoRepository,
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/photos', name: 'app_photos')]
    public function getPhotos(#[MapQueryString] ?HolidayParamsInput $params): JsonResponse
    {
        if (!$params) {
            $photos = $this->photoRepository->findAll();
        }else{
            $photos = $this->photoRepository->findByParams($params);
        }

        $serializedData = $this->serializer->serialize($photos, 'json', [
            AbstractNormalizer::GROUPS => ['photos:read']
        ]);

        return new JsonResponse($serializedData, 200, [], true);
    }
}
