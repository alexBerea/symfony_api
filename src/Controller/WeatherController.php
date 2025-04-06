<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'weather', methods: ['GET'])]
    public function getWeather(string $city, WeatherService $weatherService, Request $request): Response
    {
        $data = $weatherService->getWeather($city);

        if ($request->query->get('format') === 'json') {
            return $this->json($data);
        }

        return $this->render('weather/show.html.twig', [
            "error" => $data['error'] ?? "",
            'city' => $data['city'] ?? "",
            'temperature' => $data['temperature'] ?? "",
            'condition' => $data['condition'] ?? "",
            'humidity' => $data['humidity'] ?? "",
            'wind_speed' => $data['wind_speed'] ?? "",
            'last_updated' => $data['last_updated'] ?? "",
        ]);
    }
}