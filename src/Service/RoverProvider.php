<?php

namespace App\Service;

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
            $rover = new Rover($rover['name'], $rover['launch_date'], $rover['max_date'], $rover['status']);
            $this->entityManager->persist($rover);
        }

        $this->entityManager->flush();
    }

    public function getRovers()
    {
        $params = [
            'api_key' => $this->nasaApiKey,
        ];

        $apiParams = http_build_query($params, $arg_separator = "&",);
        $response = $this->client->request(
            'GET',
            $this->nasaApiUrl . $apiParams
        );

        return $response->toArray()['rovers'];
    }
}