<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageProvider
{
    public function __construct(
        private HttpClientInterface $client,
        private SerializerInterface $serializer,
        #[Autowire('%env(NASA_API_KEY)%')] private string $nasaApiKey,
        #[Autowire('%env(NASA_API_ENDPOINT)%')] private string $nasaApiUrl,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function getImages(string $date)
    {
        $params = [
            'earth_date' => $date,
            'api_key' => $this->nasaApiKey
        ];
        $apiParams = http_build_query($params, $arg_separator = "&",);
        $response = $this->client->request(
            'GET',
            $this->nasaApiUrl . "?" . $apiParams
        );
        $response = $response->toArray();
        dd(
            $response
        );

    }
}