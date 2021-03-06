<?php
/**
 * Created by PhpStorm.
 * User: ntezi
 * Date: 06/07/2016
 * Time: 22:33
 */
/* @var $model backend\models\place\Place */
/* @var $service backend\models\place\Service */
use yii\helpers\Html;

$session = Yii::$app->session;
$category_id = $session->get('category_id');
$service = $model->getThisPlaceHasServiceByCategory($category_id)
?>
<div class="col-sm-6 col-lg-4 item" data-key="<?= $model->id ?>">
    <div class="card-simple" data-background-image="<?php echo $model->getThumbnailLogo() ?>">
        <div class="card-simple-background">
            <div class="card-simple-content">
                <h2>
                    <a href="<?php echo $model->getPlaceUrl() ?>" target="_blank"><?php echo $model->name ?></a>
                </h2>

                <div class="card-simple-rating">
                    <?php echo $model->getRatingStars() ?>
                </div>

                <div class="card-simple-actions">
                    <a href="<?php echo $model->getPlaceUrl() ?>" target="_blank" class="fa fa-eye"></a>
                </div>
            </div>
            <div class="card-simple-label"><?php echo $service->name ?></div>
            <div class="card-simple-price" style="opacity: 0.7"><small><?php echo $model->street ?></small></div>
        </div>
    </div>
</div>