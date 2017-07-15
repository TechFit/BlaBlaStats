<?php


namespace app\models;

use yii\httpclient\Client;

class GenerateTripModel
{
    /** API Key from BlaBlaCar */
    const API_KEY = 'e94c9b2fb5be461c9b6dd00bb944a799';

    /**
     * @param $from_city string
     * @return array
     */
    public function sendRequestToApi($from_city)
    {
        $client = new Client();
        $citiesListFromDb = Cities::find()->limit(50)->orderBy('country_id DESC')->asArray()->all();
        $responseFromAPI = [];
        foreach ($citiesListFromDb as $toCity) {
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('https://public-api.blablacar.com/api/v2/trips?
                    ')
                ->setHeaders([
                    "accept: application/json",
                ])
                ->setData([
                    'key' => 'e94c9b2fb5be461c9b6dd00bb944a799',
                    'fn' => $from_city,
                    'tn' => $toCity['city_title'],
                    'locale' => 'ru_RU',
                    'cur' => 'UAH',
                    'radius' => 1,
                ])
                ->send();
            if ($response->isOk) {
                $trip = $response->getData();
                $listOfPrice = [];
                $totalTrips = count($trip['trips']);

                if (!empty($trip['trips'])) {
                    foreach ($trip['trips'] as $trip) {
                        $listOfPrice[] = $trip['price_without_commission']['value'];
                    }
                    $responseFromAPI[$toCity['city_title']]['fn'] = $from_city;
                    $responseFromAPI[$toCity['city_title']]['tn'] = $toCity['city_title'];
                    $responseFromAPI[$toCity['city_title']]['average_price'] = floor(array_sum($listOfPrice) / $totalTrips);
                    $responseFromAPI[$toCity['city_title']]['min'] = $minPrice = min($listOfPrice);
                }
            }
        }

        return $responseFromAPI;
    }
}