<?php

namespace App\Tests\Service;

use App\Service\WeatherService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;

class WeatherServiceTest extends TestCase
{
    public function testGetWeatherReturnsValidData()
    {
        $mockResponseData = [
            'location' => ['name' => 'Kiev', 'country' => 'Ukraine'],
            'current' => [
                'temp_c' => 15,
                'condition' => ['text' => 'Ясно'],
                'humidity' => 45,
                'wind_kph' => 12,
                'last_updated' => '2024-01-01 12:00'
            ]
        ];

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn($mockResponseData);

        $mockClient = $this->createMock(HttpClientInterface::class);
        $mockClient->method('request')->willReturn($mockResponse);

        $mockLogger = $this->createMock(LoggerInterface::class);

        $service = new WeatherService($mockClient, $mockLogger, 'fake-api-key');
        $result = $service->getWeather('Moscow');

        $this->assertEquals(15, $result['temperature']);
        $this->assertEquals('Ясно', $result['condition']);
        $this->assertEquals(12, $result['wind_speed']);
        $this->assertEquals('Kiev', $result['city']);
    }

    public function testGetWeatherHandlesHttpError()
    {
        $mockClient = $this->createMock(HttpClientInterface::class);
        $mockClient->method('request')->willThrowException(new \Exception('API Error'));

        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('error');

        $service = new WeatherService($mockClient, $mockLogger, 'fake-api-key');
        $result = $service->getWeather('Kiev');

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Не удалось получить данные о погоде.', $result['error']);
    }
}