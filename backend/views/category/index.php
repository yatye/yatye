<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="background-white p30 mb50">
    <h4 class="page-title"><?= Html::encode($this->title) ?></h4>
    <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-primary']) ?>
    <div class="row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'slug',
                'description',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {status}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(Html::tag('i', '', ['class' => 'fa fa-eye']), $url, ['class' => 'btn btn-primary btn-xs']);
                        },
                        'update' => function ($url, $model) {
                            return Html::a(Html::tag('i', '', ['class' => 'fa fa-edit']), $url, ['class' => 'btn btn-secondary btn-xs']);
                        },
                        'status' => function ($url, $model) {
                            return Html::a(Html::tag('i', '', ['class' => ($model['status'] == Yii::$app->params['inactive']) ? 'fa fa-check' : 'fa fa-times']), Yii::$app->request->baseUrl . '/category/status/?id=' . $model['id'], [
                                'class' => 'btn btn-primary btn-xs',
                            ]);
                        },
                    ],


                ],
            ],
        ]); ?>
    </div>
</div>
