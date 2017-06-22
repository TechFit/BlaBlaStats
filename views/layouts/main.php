<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<!--https://api.vk.com/method/database.getCountries?code=BE,BG,CZ,DK,DE,EE,IE,GR,ES,FR,HR,IT,CY,LV,LT,LU,HU,MT,NL,AT,PL,PT,RO,SI,SK,FI,SE,GB,IS,LI,NO,CH,ME,MK,AL,RS,TR,BA,AM,AZ,BY,MD,GE,UA,RU&fields=bdate&v=5.65-->
<!--https://api.vk.com/method/database.getCities?country_id=215&fields=bdate&v=5.65-->
<!--https://public-api.blablacar.com/api/v2/trips?key=e94c9b2fb5be461c9b6dd00bb944a799&fn=Kyiv&tn=Shpola&locale=uk_UA&limit=50&format=JSON-->