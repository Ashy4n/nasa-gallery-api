<?php

namespace App\Service;

use App\Entity\Holiday;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function MongoDB\BSON\toJSON;

class HolidaysProvider
{
    public function __construct(
        private HttpClientInterface $client,
        private SerializerInterface $serializer,
        #[Autowire('%env(HOLIDAY_API_KEY)%')] private string $holidaysApiKey,
        #[Autowire('%env(HOLIDAY_API_ENDPOINT)%')] private string $holidaysApiUrl,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function getHolidaysFromAPI(string $country,int $year) : array
    {
        $params = [
            'country' => $country,
            'year' => $year,
            'key' => $this->holidaysApiKey,
        ];

        $apiParams = http_build_query($params, $arg_separator = "&",);
        $response = $this->client->request(
            'GET',
            $this->holidaysApiUrl ."?". $apiParams
        );

        $content = $response->toArray();
        $holidays = $content['holidays'];

    $serialized_holidays = $this->serializer->deserialize(json_encode($holidays), Holiday::class . '[]', 'json');
    foreach ($serialized_holidays as $holiday) {
        $this->entityManager->persist($holiday);
    }
    $this->entityManager->flush();

    return $serialized_holidays;
    }
}