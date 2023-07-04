<?php

namespace App\Service;

use App\DTO\Camera;
use App\DTO\Rover;
use App\Entity\Holiday;
use App\Entity\Photo;
use App\Repository\CameraRepository;
use App\Repository\HolidayRepository;
use App\Repository\RoverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PhotoProvider
{
    public function __construct(
        #[Autowire('%env(NASA_API_KEY)%')]
        private string                 $nasaApiKey,
        #[Autowire('%env(NASA_API_ENDPOINT)%')]
        private string                 $nasaApiUrl,
        private HttpClientInterface    $client,
        private EntityManagerInterface $entityManager,
        private HolidayRepository      $holidayRepository,
        private RoverRepository        $roverRepository,
        private CameraRepository       $cameraRepository
    )
    {
    }

    public function getPhotosFromHolidays(bool $public = true)
    {
        $holidays = $public ? $this->holidayRepository->findBy(['public' => $public]) : $this->holidayRepository->findAll();
        $rovers = $this->roverRepository->findAll();
        $cameras = $this->cameraRepository->findAll();

        foreach ($rovers as $rover) {
            foreach ($cameras as $camera) {
                foreach ($holidays as $holiday) {
                    $date = $holiday->getDate()->format('Y-m-d');
                    $images = $this->getPhoto($date, $rover, $camera);
                    foreach ($images as $image) {
                        $imageSrc = $image['img_src'];
                        $photo = new Photo($holiday->getDate(), $rover, $camera, $imageSrc);
                        $this->entityManager->persist($photo);
                    }
                }
            }
        }

        $this->entityManager->flush();
    }

    public function getPhoto(string $date, string $rover, string $camera)
    {
        $params = [
            'earth_date' => $date,
            'api_key' => $this->nasaApiKey,
            'camera' => $camera
        ];

        $apiParams = http_build_query($params, $arg_separator = "&",);
        $response = $this->client->request(
            'GET',
            $this->nasaApiUrl . $rover . "/photos?" . $apiParams
        );

        return $response->toArray()['photos'];
    }
}