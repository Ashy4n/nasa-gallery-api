<?php

namespace App\Service;

use App\Entity\Camera;
use App\Entity\Holiday;
use App\Entity\Photo;
use App\Entity\Rover;
use App\Repository\CameraRepository;
use App\Repository\HolidayRepository;
use App\Repository\PhotoRepository;
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
        private PhotoRepository        $photoRepository,
        private HolidayRepository      $holidayRepository,
        private RoverRepository        $roverRepository,
        private CameraRepository       $cameraRepository
    )
    {
    }

    /**
     * @param Rover[] $rovers
     * @param Camera[] $cameras
     */

    public function getPhotosFroHolidays(array $rovers = [], array $cameras = [])
    {
        $this->photoRepository->removeAll();

        $filteredCameras = $cameras ? $cameras : $this->cameraRepository->findAll();
        $filteredRovers = $rovers ? $rovers : $this->roverRepository->findAll();
        $holidays = $this->holidayRepository->findAll();

        foreach ($filteredRovers as $rover) {
            foreach ($filteredCameras as $camera) {
                foreach ($holidays as $holiday) {
                    $date = $holiday->getDate()->format('Y-m-d');
                    $images = $this->get($date, $rover, $camera);
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

    public function get(string $date, string $rover, string $camera)
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
        dd($this->nasaApiUrl . $rover . "/photos?" . $apiParams);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error while getting holidays from API');
        }

        return $response->toArray()['photos'];
    }
}