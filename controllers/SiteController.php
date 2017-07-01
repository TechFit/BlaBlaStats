<?php

namespace app\controllers;

use app\models\Trips;
use yii\data\Pagination;
use Yii;
use yii\web\Controller,
    yii\web\NotFoundHttpException;
use yii\filters\AccessControl,
    yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string
     *
     * Main page
     */
    public function actionIndex()
    {
        $tripQuery = Trips::find();
        $countTrips = clone $tripQuery;
        $tripPages = new Pagination(['totalCount' => $countTrips->count()]);
        $tripList = $tripQuery->offset($tripPages->offset)
            ->limit($tripPages->limit)
            ->all();

        return $this->render('index', [
            'tripList' => $tripList,
            'tripPages' => $tripPages,
        ]);
    }
}
