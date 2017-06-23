<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

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
     * Get countries and cities
     */
    public function actionCities()
    {
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
            $countOfCountries = $startParser->find('#maincontent > div.row.top-departure-index.margin-top > div > div.top-departure > div.margin-top.margin-bottom.clearfix');
            $parsedObjectCountries = [];
            $parsedObjectCities = [];
            $listOfCitiesAndCountries = [];

            for ($i = 1; $i < count($countOfCountries); $i++) {
                    $parsedData = $startParser->find('#maincontent > div.row.top-departure-index.margin-top > div > div.top-departure > div.margin-top.margin-bottom.clearfix:nth-child(' . ($i) . ') h2');
                    $country = pq($parsedData)->html();
                var_dump($country);
                }
//                $parsedData[][] = $startParser->find('#maincontent > div.row.top-departure-index.margin-top > div > div.top-departure > div:nth-child(' . $i . ') > div:nth-child(1) > ul > li > a');
            }
//
//
//            foreach ($parsedData as $item) {
//                $dataFromList[] = pq($item)->html();
//                foreach ($dataFromList as $city) {
//                    $getCity = explode('Попутники з ', $city);
//                    $cities[] = trim($getCity[1]);
//                }
//            }
//            var_dump($country);

    }

    /*
     * Get statistic between cities
     */
    public function actionRoadTable()
    {

    }

}
