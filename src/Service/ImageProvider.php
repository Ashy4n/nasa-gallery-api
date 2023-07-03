<?php

namespace App\Service;

use App\DTO\Camera;
use App\DTO\Rover;
use App\Entity\Holiday;
use App\Repository\HolidayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageProvider
{
    public function __construct(
        private HttpClientInterface                            $client,
        private SerializerInterface                            $serializer,
        #[Autowire('%env(NASA_API_KEY)%')] private string      $nasaApiKey,
        #[Autowire('%env(NASA_API_ENDPOINT)%')] private string $nasaApiUrl,
        private EntityManagerInterface                         $entityManager,
        private HolidayRepository                              $holidayRepository
    )
    {
    }

    public function getImagesForHolidays(bool $public = true)
    {
        $holidays = $public ? $this->holidayRepository->findBy(['public' => $public]) : $this->holidayRepository->findAll();
        $rovers = [Rover::CURIOSITY, Rover::OPPORTUNITY, Rover::SPIRIT];
        $cameras = [Camera::RHAZ, Camera::FHAZ];

        $images = [];

        foreach ($rovers as $rover) {
            foreach ($cameras as $camera) {
                foreach ($holidays as $holiday) {
                    $date = $holiday->getDate()->format('Y-m-d');
                    $response = $this->getImage($date, $rover, $camera);
                    dd($response);
                }
            }
        }



        return $images;
    }

    public function getImage(string $date, string $rover, string $camera)
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

        return $response->toArray();
    }
}