<?php
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
$this->title = 'Статистика BlaBlaCar';
?>
<div class="site-index">
    <div class="box">
        {{ message }}
        <table class="table">
            <thead>
                <tr>
                    <th>З міста</th>
                    <th>До міста</th>
                    <th>Середня вартість</th>
                    <th>Min та Max вартість</th>
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
            <tbody>
                <?php foreach ($tripList as $trip) { ?>
                    <tr>
                        <td><?= $trip->fn ?></td>
                        <td><?= $trip->tn ?></td>
                        <td style="color: #00d1b2"><?= $trip->average_price ?></td>
                        <td><?= $trip->min_price ?> - <?=  $trip->max_price ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
        echo LinkPager::widget([
            'pagination' => $tripPages,
        ]);
        ?>
    </div>
</div>