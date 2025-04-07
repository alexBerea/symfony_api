## Symfony API
Это базовый проект API, построенный на фреймворке Symfony. Он предназначен для разработки RESTful API с использованием современных стандартов PHP.

## Требования
PHP: >= 8.1
Composer: Последняя версия
Веб-сервер (например, Apache/Nginx) или встроенный сервер Symfony

## git clone https://github.com/alexBerea/symfony_api.git
## cd symfony_api

## Установите зависимости
Убедитесь, что Composer установлен, и выполните:
## composer install

## Запустите проект
php bin/console server:run

## Коротке пояснення рішень щодо реалізації
## 1 Установка Symfony (LTS-версия 6.4) 
## 2 Установка необходимых компонентов
 composer require symfony/http-client symfony/config symfony/dotenv symfony/monolog-bundle
3 Создание сервиса Weather
class WeatherService
4 Настройка параметров

В .env 
WEATHER_API_KEY=ваш_API_ключ

В config/services.yaml:
parameters:
    weather.api_key: '%env(WEATHER_API_KEY)%'

services:
    App\Service\WeatherService:
        arguments:
            $apiKey: '%weather.api_key%'

## 5 Создание контроллера
## 6 Логирование ошибок
