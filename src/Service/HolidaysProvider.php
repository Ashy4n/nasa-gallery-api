<?php

namespace App\Service;

use App\Entity\Holiday;
use App\Repository\HolidayRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HolidaysProvider
{

    public function __construct(
        #[Autowire('%env(HOLIDAY_API_ENDPOINT)%')]
        private string              $holidaysApiUrl,
        private HttpClientInterface $client,
        private SerializerInterface $serializer,
        private HolidayRepository   $holidayRepository,
    )
    {
    }

    public function save(array $holidays): void
    {
        $serializedHolidays = $this->serializer->deserialize(json_encode($holidays), Holiday::class . '[]', 'json');
        $this->holidayRepository->saveAll($serializedHolidays);
    }

    public function get(string $country, int $year): array
    {
        $response = $this->client->request(
            'GET',
            $this->holidaysApiUrl . "{$year}/{$country}",
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error while getting holidays from API');
        }

        return $response->toArray();
    }
}