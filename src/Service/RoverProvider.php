<?php

namespace App\Service;

use App\Entity\Camera;
use App\Entity\Rover;
use App\Repository\CameraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RoverProvider
{
    public function __construct(
        #[Autowire('%env(NASA_API_KEY)%')]
        private string $nasaApiKey,
        #[Autowire('%env(NASA_API_ENDPOINT)%')]
        private string $nasaApiUrl,
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
    )
    {
    }


    public function saveRovers(array $rovers)
    {
        foreach ($rovers as $rover) {
            $newRover = new Rover();
            $newRover->setName($rover['name']);
            $newRover->setMinDate(new \DateTime($rover['launch_date']) );
            $newRover->setMaxDate(new \DateTime($rover['max_date']) );

            foreach ($rover['cameras'] as $camera){
                $newCamera = new Camera();
                $newCamera->setName($camera['name']);
                $newCamera->setFullName($camera['full_name']);

                $newRover->addCamera($newCamera);

                $this->entityManager->persist($newCamera);
            }

            $this->entityManager->persist($newRover);
        }

        $this->entityManager->flush();
    }

    public function getRovers()
    {
        $params = [
            'api_key' => $this->nasaApiKey,
        ];

        $apiParams = http_build_query($params,$arg_separator = "&",);
        $response = $this->client->request(
            'GET',
            $this->nasaApiUrl . "?". $apiParams
        );

        return $response->toArray()['rovers'];
    }
}