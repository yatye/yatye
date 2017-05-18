<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use common\models\Service;
use common\models\WorkingHours;
use backend\models\place\Gallery;
use backend\models\place\UserPlace;
use backend\models\place\PlaceHasService;
use backend\models\place\SocialMedia;
use backend\models\place\Place as BasePlace;

class Place extends BasePlace
{
    public function getPhotos()
    {
        return Gallery::find()->where(['place_id' => $this->id, 'status' => Yii::$app->params['active']])->all();
    }

    public function getHours()
    {
        return WorkingHours::find()->where(['place_id' => $this->id, 'status' => Yii::$app->params['active']])->orderBy('id')->all();
    }

    public function getSocials()
    {
        return SocialMedia::findAll(['place_id' => $this->id]);
    }

    public function getAmenities()
    {
        $sql = "SELECT `service`.* 
                FROM `service`, `place_has_service` 
                WHERE `place_has_service`.`service_id` = `service`.`id` 
                AND `place_has_service`.`place_id` = " . $this->id . "  
                ORDER BY RAND()";
        return self::findBySql($sql)->all();
    }

    public function getAllAmenities()
    {
        return Service::find()->where(['category_id' => 5])->all();
    }

    public function getViews()
    {
        $view = Views::findOne(['place_id' => $this->id, 'status' => Yii::$app->params['active']]);
        return (!empty($view)) ? $view->views : 0;
    }

    public function getRelatedPlaceIds()
    {

        $service_ids = (new Query())
            ->select('`service_id`')
            ->from('`place_has_service`')
            ->where(['place_id' => $this->id])
            ->all();

        $place_ids = (new Query())
            ->select(new Expression('DISTINCT `place_id`'))
            ->from('`place_has_service`')
            ->where(['in', 'service_id', $service_ids])
            ->andWhere(['!=', 'place_id', $this->id])
            ->all();

        $ids = array();
        foreach ($place_ids as $place_id) {
            $ids[] = $place_id['place_id'];
        }

        return $ids;
    }

    public function getRelatedPlaces()
    {
        $place_ids = $this->getRelatedPlaceIds();

        $sql = "SELECT `place`.*
                FROM `place` 
                WHERE `id` IN (" . implode(',', $this->getRelatedPlaceIds()) . ")
                AND `status`= " . Yii::$app->params['active'] . "
                ORDER BY RAND() LIMIT 4 ";

        return self::findBySql($sql);
        /*return self::find()
            ->where(['in', 'id', $place_ids])
            ->andWhere(['status' => Yii::$app->params['active']])
            ->orderBy(new Expression('RAND()'));*/
    }

    public static function getMyPlaces()
    {
        return $query = (new Query())
            ->select('`place`.`id`')
            ->addSelect('`place`.`name`')
            ->addSelect('`place`.`slug`')
            ->addSelect('`place`.`logo`')
            ->from('`user_place`, `place`')
            ->where('`user_place`.`user_id` = ' . Yii::$app->user->identity->id)
            ->andWhere('`user_place`.`place_id` = `place`.`id`')
//            ->andWhere('`place`.`status` = ' . Yii::$app->params['active'])
            ->all();
    }

    public function hasUser()
    {
        $user_place = UserPlace::find()->where(['user_id' => Yii::$app->user->identity->id, 'place_id' => $this->id])->all();
        if (!empty($user_place)) :

            return true;
        else: return
            false;
        endif;
    }

    public function getOtherPlaces()
    {
        return parent::getOtherPlaces(); // TODO: Change the autogenerated stub
    }

}
