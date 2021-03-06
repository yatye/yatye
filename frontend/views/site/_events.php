<?php
/**
 * Created by PhpStorm.
 * User: ntezi
 * Date: 27/12/2016
 * Time: 22:34
 */
/* @var $event backend\models\Event */
use yii\helpers\Url;

?>
    <div class="container">
        <div id="events" class="block background-white p30 mt30 mb30 row div">
            <div class="text-center" style="margin-top: -30px">
                <h1><?php echo Yii::t('app', 'Upcoming Events') ?></h1>
            </div>
            <div class="cards-wrapper">
                <div class="row">
                    <div class="grid">
                        <?php if (!empty($up_coming_events)):
                            foreach ($up_coming_events as $event):?>
                                <div class="grid-item col-xs-12 col-sm-3">
                                    <div class="card" data-background-image="<?php echo $event->getBanner(); ?>">
                                        <div class="card-label">
                                            <a target="_blank"
                                               href="<?php echo $event->getEventUrl() ?>">
                                                <?php echo $event->getDate() ?>
                                            </a>
                                        </div>
                                        <div class="card-content">
                                            <h2 style="font-size: 18px;">
                                                <a target="_blank"
                                                   href="<?php echo $event->getEventUrl() ?>">
                                                    <?php echo $event->name ?>
                                                </a>
                                            </h2>
                                            <?php if ($event->address != null): ?>
                                                <div class="card-meta">
                                                    <i class="fa fa-map-o"></i> <?php echo $event->address ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-actions">
                                                <a target="_blank" href="<?php echo $event->getEventUrl() ?>"
                                                   class="fa fa-eye"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php $this->registerJs("
        $(function(){
            var m = new Masonry($('.grid').get()[0], {
                itemSelector: \".grid-item\"
            });
        });
    "); ?>