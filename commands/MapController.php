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
     * Get list of countries
     */
    public function actionCountries()
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
            $data = $startParser->find('#maincontent > div.row.top-departure-index.margin-top > div > div.row > div > ul  li  a');
            $countries = [];
            $countriesModel = new Countries();
            foreach ($data as $item) {
                $countries[] = pq($item)->html();
                $countriesModel->country_title = pq($item)->html();
                $countriesModel->insert();
            }
            var_dump($countries);
        }
    }

    /**
     * Get list of cities
     */
    public function actionCities()
    {

    }

    /*
     * Get statistic between cities
     */
    public function actionRoadTable()
    {

    }

}
