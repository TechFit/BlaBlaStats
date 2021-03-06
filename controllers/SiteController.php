<?php

namespace app\controllers;

use app\models\GenerateTripModel;
use app\models\GenerateTripsForm;
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

    /**
     * @return string
     *
     * Find trip from city page
     */
    public function actionFindTrip()
    {
        $formTrip = new GenerateTripsForm();
        $tripModel = new GenerateTripModel();

        if ($formTrip->load(Yii::$app->request->post()) && $formTrip->validate()) {
            $tripList = $tripModel->sendRequestToApi($formTrip->fromCity);
        }

        if (empty($tripList)) {
            $tripList = [];
        }

        return $this->render('find-trip', [
            'tripList' => $tripList,
            'formTrip' => $formTrip,
        ]);
    }
}
