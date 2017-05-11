<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = Yii::$app->name;

?>
<div class="main">
    <div class="main-inner" style="padding: 1px">
        <div class="content">
            <!--Home -->
            <?php echo $this->render('_home', [
                    'service_categories' => $service_categories,
                ]); ?>

            <!--Advertisement Banners-->
            <?php //echo $this->render('_ads', []); ?>

            <!--Up coming events-->
            <?php $data = $this->context->accessData();
            $upcoming_events = $data['get_upcoming_events'];
            $count = count($upcoming_events);
            if (!empty($upcoming_events) && $count >= 4) :
                echo $this->render('_events', [
                    'up_coming_events' => $upcoming_events,
                ]);
            endif; ?>

        </div>
    </div>
</div>
<?php $this->registerJs("
        $(function(){
            var m = new Masonry($('.grid').get()[0], {
                itemSelector: \".item\"
            });
        });
    "); ?>
