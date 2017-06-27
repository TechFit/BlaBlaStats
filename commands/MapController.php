<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Cities;
use app\models\Countries;
use app\models\Trips;
use Yii;
use phpQuery;
use yii\console\Controller;
use yii\httpclient\Client;

/**
 * This command for getting actual data from BlaBlaCar
 *
 * @author Maxym Barvinok <nempak@gmail.com>
 */
class MapController extends Controller
{
    /**
     * Manually adding country
     */
    public function actionAddCountryManually($title, $code)
    {
        $countryId = Countries::findOne(['country_title' => $title]);

        if (!isset($countryId->id)) {
            $countriesTable = new Countries();
            $countriesTable->country_title = $title;
            $countriesTable->code = $code;
            $countriesTable->save();

            echo "Done \n";
            return 0;
        } else {
            echo "Already in base \n";
            return 1;
        }
    }

    /**
     * Import countries from BlaBlaCar
     * @return int
     */
    public function actionAddCountries()
    {
        $commandStartTime = new \DateTime('now');

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://www.blablacar.com.ua/poshuk-poputnikiv-za-kordon/')
            ->addHeaders([
                'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
                'Content-Type: text/html; charset=utf-8'
            ])
            ->send();

        if ($response->isOk) {
            $startParser = phpQuery::newDocument($response);
            // Получаю блоки с странами
            $parsedObject = $startParser->find(
                '#maincontent > div.row.top-departure-index.margin-top > 
                div > div.top-departure > div.margin-top.margin-bottom.clearfix h2
                ');

            foreach ($parsedObject as $item) {
                // Получаю текстовое название страны и код
                $countryTitle = pq($item)->html();
                $countryCode = pq($item)->attr('id');
                // Сохраняю города в БД
                self::actionAddCountryManually($countryTitle, $countryCode);
            }
        }

        $commandEndTime = new \DateTime('now');
        $timeOfWork = $commandEndTime->getTimestamp() - $commandStartTime->getTimestamp();
        echo "Import Countries Job done " . $timeOfWork . "\n";
        return 0;
    }

    /**
     * Import Cities from BlaBlaCar
     * @return int
     */
    public function actionAddCities()
    {
        $commandStartTime = new \DateTime('now');

        $client = new Client();

        $countriesListFromDb = Countries::find()->asArray()->all();

        $alphabet = range('A', 'Z');

        foreach ($countriesListFromDb as $country) {
            foreach ($alphabet as $word) {
                $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl('https://www.blablacar.com.ua/poshuk-poputnikiv-za-kordon/' . $country['code'] . '/' . $word)
                    ->addHeaders([
                        'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36',
                        'Content-Type: text/html; charset=utf-8'
                    ])
                    ->send();

                if ($response->isOk) {
                    $startParser = phpQuery::newDocument($response);
                    $parsedDataCities = $startParser->find('
                    #maincontent > div.row.top-departure-index.margin-top > div > div > div.row div.span4 ul li a
                ');
                    // Получаю название города убрав в строке "Попутники з"
                    $prepareCity[] = explode('Попутники з', pq($parsedDataCities)->html());
                    $city = [];
                    // Удаляю пробелы и пустые элементы массива
                    foreach ($prepareCity as $item) {
                        $city = array_diff(array_map('trim', $item), array(''));
                    }

                    //Записываю города в БД
                    foreach ($city as $item) {
                        $countryId = Countries::findOne(['country_title' => $country['country_title']]);
                        $cityId = Cities::findOne(['city_title' => $item]);
                        if (!isset($cityId->id) and isset($countryId->id)) {
                            $citiesTable = new Cities();
                            $citiesTable->country_id = $countryId->id;
                            $citiesTable->city_title = $item;
                            $citiesTable->save();
                        }
                    }
                }
            }
            echo 'Done - ' . $country['country_title'] . "\n";
        }

        $commandEndTime = new \DateTime('now');
        $timeOfWork = $commandEndTime->getTimestamp() - $commandStartTime->getTimestamp();
        echo "Import Cities Job done " . $timeOfWork . "\n";
        return 0;
    }

    /*
     * Get statistic between cities
     */
    public function actionRoadTable()
    {
        $commandStartTime = new \DateTime('now');

        $client = new Client();

        $citiesListFromDb = Cities::find()->asArray()->all();

        foreach ($citiesListFromDb as $fromCity) {
            unset($citiesListFromDb[$fromCity['city_title']]);
            foreach ($citiesListFromDb as $toCity) {
                echo 'С ' . $fromCity['city_title'] . ' В ' . $toCity['city_title'] . "\n";
                $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl('https://public-api.blablacar.com/api/v2/trips?
                    ')
                    ->setHeaders([
                        "accept: application/json",
                    ])
                    ->setData([
                        'key' => 'e94c9b2fb5be461c9b6dd00bb944a799',
                        'fn' => $fromCity['city_title'],
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
                        $minPrice = min($listOfPrice) . "\n";
                        $averagePrice = floor(array_sum($listOfPrice) / $totalTrips) . "\n";
                        $maxPrice = max($listOfPrice) . "\n";
                        $trip = new Trips();
                        $trip->fn = $fromCity['city_title'];
                        $trip->tn = $toCity['city_title'];
                        $trip->fn_country_id = $fromCity['country_id'];
                        $trip->tn_country_id = $toCity['country_id'];
                        $trip->min_price = $minPrice;
                        $trip->average_price = $averagePrice;
                        $trip->max_price = $maxPrice;
                        $trip->save();
                    }
                }
            }
        }

        $commandEndTime = new \DateTime('now');
        $timeOfWork = $commandEndTime->getTimestamp() - $commandStartTime->getTimestamp();
        echo "Create Road Table Job done " . $timeOfWork . "\n";
        return 0;
    }

}
