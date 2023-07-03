<?php

namespace App\Service;

use App\Entity\Holiday;
use App\Repository\HolidayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HolidaysProvider
{

    public function __construct(
        #[Autowire('%env(HOLIDAY_API_KEY)%')] private string $holidaysApiKey,
        #[Autowire('%env(HOLIDAY_API_ENDPOINT)%')] private string $holidaysApiUrl,
        private HttpClientInterface $client,
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
        private HolidayRepository $holidayRepository,
    )
    {
    }

    public function save(array $holidays) : void
    {
        $this->holidayRepository->removeAll();

        $serializedHolidays = $this->serializer->deserialize(json_encode($holidays) , Holiday::class . '[]', 'json');

        foreach ($serializedHolidays as $holiday) {
            $this->holidayRepository->save($holiday);
        }

        $this->entityManager->flush();
    }

    public function get(string $country,int $year) : array
    {
        $params = [
            'country' => $country,
            'year' => $year,
            'key' => $this->holidaysApiKey,
        ];

        $apiParams = http_build_query($params, $arg_separator = "&",);
        $response = $this->client->request(
            'GET',
            $this->holidaysApiUrl . "?" . $apiParams
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error while getting holidays from API');
        }

        $content = $response->toArray();

        return $content['holidays'];
    }
}