<?php


namespace App\Http\Controllers\Api\WeatherPredictor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Exceptions\InvalidDateException;
use WeatherPredictor\Exceptions\ScaleNotFoundException;
use WeatherPredictor\Services\Forecasts;

class PredictorController extends Controller
{

    use Helpers;

    /**
     * @param Request $request
     * @param Forecasts $forecasts
     * @param string $city
     * @param string $scale
     * @param null $date
     * @return JsonResponse
     */
    public function __invoke(Request $request, Forecasts $forecasts, string $city, string $scale, $date = null)
    {
        try {
            /** Validate the date with the given rules */
            $this->assertValidDate($date);

            /** @var Carbon $forecastDay */
            $forecastDay = $date ? Carbon::createFromFormat('Y-m-d', $date) : Carbon::now();

            /** @var TemperatureScaleContract $selectedTemperature */
            $selectedTemperature = TemperatureFactory::getTemperatureFromName($scale);

            /** @var array $forecastData */
            $forecastData = $forecasts->doTheMagic($city, $selectedTemperature, $forecastDay);

            return new JsonResponse([
                'statusCode' => 200,
                'day' => $forecastDay->format('Y-m-d'),
                'outputScale' => $selectedTemperature->getName(),
                'outputScaleSymbol' => $selectedTemperature->getSymbol(),
                'city' => $city,
                'forecast' => $forecastData,
            ]);

        } catch (ScaleNotFoundException $e) {
            return new JsonResponse([
                'statusCode' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        } catch (InvalidDateException $e) {
            return new JsonResponse([
                'statusCode' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'statusCode' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param null $date
     * @throws InvalidDateException
     */
    private function assertValidDate($date = null) : void
    {
        if($date && ! is_valid_date($date, 'Y-m-d'))
        {
            throw new InvalidDateException('The date given is invalid, please be sure is in the format YYYY-MM-DD', JsonResponse::HTTP_BAD_REQUEST);
        }

        $carbonDate = $date ? Carbon::createFromFormat('Y-m-d', $date) : Carbon::now();
        $dateFloor = Carbon::now();
        $dateTop = Carbon::now()->addDays(10);

        if (!$carbonDate->isBetween($dateFloor->startOfDay(), $dateTop->endOfDay())) {
            throw new InvalidDateException('The date given is out of the forecast range.', JsonResponse::HTTP_BAD_REQUEST);
        }

    }
}
