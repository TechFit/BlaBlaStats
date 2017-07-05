<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\widgets\ActiveForm,
    yii\widgets\Pjax;

/* @var $this yii\web\View */
$this->title = 'Статистика BlaBlaCar';
?>


<?php Pjax::begin(['id' => 'notes']) ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

<?= $form->field($formTrip, 'fromCity')->input('text')->label('Из города:') ?>

<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
<div class="site-index">
    <div class="box">
        <table class="table">
            <thead>
            <tr>
                <th>З міста</th>
                <th>До міста</th>
                <th>Середня вартість</th>
                <th>Min вартість</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>З міста</th>
                <th>До міста</th>
                <th>Середня вартість</th>
                <th>Min та Max вартість</th>
            </tr>
            </tfoot>
            <tbody id="notes">
            <?php foreach ($tripList as $trip) { ?>
                <tr>
                    <td><?= isset($trip['fn']) ? $trip['fn'] : '' ?></td>
                    <td><?= isset($trip['tn']) ? $trip['tn'] : '' ?></td>
                    <td><?= isset($trip['min_price']) ? $trip['min_price'] : '' ?></td>
                    <td style="color: #00d1b2"><?= isset($trip['average_price']) ? $trip['average_price'] : '' ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php Pjax::end() ?>