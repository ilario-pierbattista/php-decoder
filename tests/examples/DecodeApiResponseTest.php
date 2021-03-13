<?php declare(strict_types=1);

namespace Examples\Pybatt\Codec;

use Examples\Pybatt\Codec\in\Coordinates;
use Pybatt\Codec\Codecs;
use Tests\Pybatt\Codec\BaseTestCase;

class DecodeApiResponseTest extends BaseTestCase
{
    public function testJsonDecoding(): void
    {
        $codec = Codecs::classFromArray(
            [
                'coord' => Codecs::classFromArray(
                    [
                        'lon' => Codecs::float(),
                        'lat' => Codecs::float()
                    ],
                    function (float $lon, float $lat): in\Coordinates {
                        return new in\Coordinates($lon, $lat);
                    },
                    in\Coordinates::class
                ),
                'weather' => Codecs::listt(
                    Codecs::classFromArray(
                        [
                            'id' => Codecs::int(),
                            'main' => Codecs::string(),
                            'description' => Codecs::string()
                        ],
                        function (int $id, string $main, string $desc): in\Weather {
                            return new in\Weather($id, $main, $desc);
                        },
                        in\Weather::class
                    )
                )
            ],
            function (Coordinates $coordinates, array $weathers): in\OpenWeatherResponse {
                return new in\OpenWeatherResponse($coordinates, $weathers);
            },
            in\OpenWeatherResponse::class
        );

        $result = $codec->decode(json_decode(self::weatherJson(), true));

        self::asserSuccessInstanceOf(
            in\OpenWeatherResponse::class,
            $result
        );
    }

    private static function weatherJson(): string
    {
        return <<<JSON
{
  "coord": {
    "lon": 13.6729,
    "lat": 43.2027
  },
  "weather": [
    {
      "id": 804,
      "main": "Clouds",
      "description": "overcast clouds",
      "icon": "04d"
    }
  ],
  "base": "stations",
  "main": {
    "temp": 286.82,
    "feels_like": 286.01,
    "temp_min": 285.93,
    "temp_max": 288.15,
    "pressure": 1015,
    "humidity": 74
  },
  "visibility": 10000,
  "wind": {
    "speed": 0.89,
    "deg": 270,
    "gust": 0.89
  },
  "clouds": {
    "all": 100
  },
  "dt": 1615564151,
  "sys": {
    "type": 3,
    "id": 2001891,
    "country": "IT",
    "sunrise": "2021-03-12T06:22:48.000+01:00",
    "sunset": "2021-03-12T18:07:28.000+01:00"
  },
  "timezone": 3600,
  "id": 3172720,
  "name": "Monte Urano",
  "cod": 200
}
JSON;
    }
}

namespace Examples\Pybatt\Codec\in;

class Coordinates
{
    /** @var float */
    private $longitude;
    /** @var float */
    private $latitude;

    public function __construct(float $longitude, float $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}

class OpenWeatherResponse
{
    /** @var Coordinates */
    private $coordinates;
    /** @var array */
    private $weather;

    public function __construct(Coordinates $coordinates, array $weathers)
    {
        $this->coordinates = $coordinates;
        $this->weather = $weathers;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function getWeather(): array
    {
        return $this->weather;
    }
}

class Weather
{
    /** @var int */
    private $id;
    /** @var string */
    private $main;
    /** @var string */
    private $description;

    public function __construct(int $id, string $main, string $description)
    {
        $this->id = $id;
        $this->main = $main;
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMain(): string
    {
        return $this->main;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}