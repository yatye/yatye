<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PricingItem */

$this->title = Yii::t('app', 'Add Pricing Item');
?>
<div class="row">
    <div class="col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
