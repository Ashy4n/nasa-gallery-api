<?php

namespace App\Service;

use App\Entity\Holiday;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HolidaysProvider
{
    public function __construct(
        private HttpClientInterface $client,
        private SerializerInterface $serializer,
        #[Autowire('%env(HOLIDAY_API_KEY)%')] private string $holidaysApiKey,
        #[Autowire('%env(HOLIDAY_API_ENDPOINT)%')] private string $holidaysApiUrl,
    )
    {
    }

    public function getHolidaysFromAPI($country, $year)
    {
        $params = [
            'country' => $country,
            'year' => $year,
            'key' => $this->holidaysApiKey,
        ];

        $apiParams = http_build_query($params, $arg_separator = "&",);

        $response = $this->client->request(
            'GET',
            $this->holidaysApiUrl . $apiParams
        );


        $response = $this->client->request('GET', 'https://holidayapi.com/v1/holidays?country=PL&year=2022&key=80d615c5-338b-45d0-a1c9-8d015e371904');


        $content = $response->getContent();

        $x = $this->serializer->deserialize($content, Holiday::class, 'json');

        dd($x);

//        $statusCode = $response->getStatusCode();
//        $content = $response->toArray();
//        dd($content["holidays"]);
    }
}