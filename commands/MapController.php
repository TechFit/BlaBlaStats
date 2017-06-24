<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Cities;
use app\models\Countries;
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
    public function actionAddCountryManually($code, $title)
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
     * Get countries and cities
     * @return int
     */
    public function actionCities()
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
            // Получаю блоки с странами и городами
            $parsedObject = $startParser->find('#maincontent > div.row.top-departure-index.margin-top > div > div.top-departure > div');

            $counterForCities = 0;

            for ($i = 1; $i < count($parsedObject); $i++) {
                // Получаю блок с h2 в котором название города, последовательность 1,3,5...
                $parsedDataCountries = $startParser->find('
                    #maincontent > div.row.top-departure-index.margin-top > 
                    div > div.top-departure > div.margin-top.margin-bottom.clearfix:nth-child(' . ($i) . ') h2
                    ');

                // Получаю текстовое название города
                $countryTitle = pq($parsedDataCountries)->html();
                $countryCode = pq($parsedDataCountries)->attr('id');
                // Сохраняю города в БД
                self::actionAddCountryManually($countryCode, $countryTitle);
                // Увеличил счетчик для получения блока с городами, последовательность 2,4,6...
                $i += 1;

                // Получаю массив с городами
                $parsedDataCities = $startParser->find('
                    #maincontent > div.row.top-departure-index.margin-top > 
                    div > div.top-departure > div:nth-child(' . $i . ') > div:nth-child(1) > ul > li > a
                ');

                // Получаю название города убрав в строке "Попутники з"
                $prepareCity[] = explode('Попутники з', pq($parsedDataCities)->html());
                // Удаляю пробелы и пустые элементы массива
                $city = array_diff(array_map('trim', $prepareCity[$counterForCities]), array(''));

                $counterForCities++;
                 // Записываю города в БД
                foreach ($city as $item) {
                    $countryId = Countries::findOne(['country_title' => $countryTitle]);
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

        $commandEndTime = new \DateTime('now');
        $timeOfWork = $commandEndTime->getTimestamp() - $commandStartTime->getTimestamp();
        echo "Import Countries/Cities Job done " . $timeOfWork . "\n";
        return 0;
    }

    /*
     * Get statistic between cities
     */
    public function actionRoadTable()
    {

    }

}
