<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"
      itemscope itemtype="http://schema.org/Website">
<head>
    <!--Recommended Meta Tags-->
    <?php echo $this->render('@app/views/layouts/meta-tags/_recommended_meta_tags') ?>

    <!--Search Engine Optimization Meta Tags-->
    <?php echo $this->render('@app/views/layouts/meta-tags/_seo_meta_tags') ?>

    <!-- Schema.org markup for Google+ -->
    <?php //echo $this->render('@app/views/layouts/meta-tags/_google_meta_tags') ?>

    <!-- Twitter Card data -->
    <?php //echo $this->render('@app/views/layouts/meta-tags/_twitter_meta_tags') ?>

    <!-- Open Graph data -->
    <?php //echo $this->render('@app/views/layouts/meta-tags/_open_graph_data_meta_tags') ?>

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>

    <!--pre-load-->
    <?php $this->registerCss(".pre-load{display: none;} ") ?>

    <!--Link-->
    <?php echo $this->render('@app/views/layouts/meta-tags/_link_meta_tags') ?>

    <!--Scripts-->
    <?php echo $this->render('@app/views/layouts/scripts/_facebook') ?>
    <?php echo $this->render('@app/views/layouts/scripts/_google') ?>

</head>
<body>
<?php $this->beginBody() ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=1569960559930538";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div class="page-wrapper">
    <?php echo $this->render('@app/views/layouts/header/_header') ?>
    <?php echo $this->render('@app/views/layouts/_messages') ?>
    <div class="main">
        <div class="main-inner">
            <div class="content mt-70">
                <div class="container">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->render('@app/views/layouts/_footer') ?>
</div>
<?php echo $this->render('@app/views/layouts/scripts/_twitter') ?>
<?php echo $this->render('@app/views/layouts/scripts/_other_scripts') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
