<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class WeatherService
{
    private HttpClientInterface $client;
    private string $apiKey;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, string $apiKey)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
    }

    public function getWeather(string $city): array
    {
        try {
            $response = $this->client->request('GET', 'https://api.weatherapi.com/v1/current.json', [
                'query' => [
                    'q' => $city,
                    'key' => $this->apiKey,
                ],
            ]);

            return $this->getDataWeather($response->toArray());

        } catch (\Throwable $e) {
            $this->logger->error('Error while getting weather: ' . $e->getMessage());
            return ['error' => 'Failed to retrieve weather data.'];
        }
    }

    public function getDataWeather($data)
    {
        return [
            'city' => $data['location']['name'] ?? "",
            'country' => $data['location']['country'] ?? "",
            'temperature' => $data['current']['temp_c'] ?? "",
            'condition' => $data['current']['condition']['text'] ?? "",
            'humidity' => $data['current']['humidity'] ?? "",
            'wind_speed' => $data['current']['wind_kph'] ?? "",
            'last_updated' => $data['current']['last_updated'] ?? "",
        ];
    }
}