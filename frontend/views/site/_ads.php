<?php
/**
 * Created by PhpStorm.
 * User: mariusngaboyamahina
 * Date: 5/11/17
 * Time: 1:52 PM
 */
/* @var $ad backend\models\Ads */
?>

<div class="container">
    <div class="row block background-white p30 mt30 div">
        <div class="cards-wrapper">
            <div class="row">
                <!--730x300-->
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <div style="margin: 0px 0px 30px 0px;">
                                <div class="carousel slide carousel-fade" data-ride="carousel" data-interval="2500" data-wrap="true">
                                    <div class="carousel-inner">
                                        <?php $active = ' active';
                                        foreach ($ads['730x300'] as $ad) :
                                            if ($ad->image != '' || $ad->image != null) : ?>
                                                <div class="item <?php echo $active; ?>">
                                                    <img style="width: 730px; height: 300px;"
                                                         src="<?php echo $ad->getPath() ?>"
                                                         alt="<?php echo $ad->title ?>">
                                                </div>
                                            <?php endif;
                                            $active = null;
                                        endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--300x300-->
                    <div class="row">
                        <?php foreach ($ads['300x300'] as $ad) :
                            if ($ad->image != '' || $ad->image != null) : ?>
                                <div class="col-sm-6">
                                    <img style="max-width: 100%;max-height: 100%;"
                                         src="<?php echo $ad->getPath() ?>"
                                         alt="<?php echo $ad->title ?>">
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>

                <!--350x630-->
                <div class="col-sm-4">
                    <div class="card-tall">
                        <div class="carousel slide" data-ride="carousel" data-wrap="true">
                            <div class="carousel-inner">
                                <?php $active = ' active';
                                foreach ($ads['350x630'] as $ad) :
                                    if ($ad->image != '' || $ad->image != null) : ?>
                                        <div class="item <?php echo $active; ?>">
                                            <img style="width: 350px;height: 630px;"
                                                 src="<?php echo $ad->getPath() ?>"
                                                 alt="<?php echo $ad->title ?>">
                                        </div>
                                    <?php endif;
                                    $active = null;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
