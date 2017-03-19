<?php

namespace frontend\models;

use backend\models\Address;
use backend\models\Contact;
use backend\models\Gallery;
use backend\models\PlaceService;
use backend\models\SocialMedia;
use backend\models\UserPlace;
use common\models\Service;
use common\models\WorkingHours;
use Yii;
use backend\models\Place as BasePlace;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

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
        $query = new Query();
        $select = $query
            ->select('`service`.`id`')
            ->addSelect('`service`.`name`')
            ->addSelect('`service`.`slug`')
            ->from('`service`, `place_service`, `category`')
            ->where('`place_service`.`service_id` = `service`.`id`')
            ->andWhere('`place_service`.`place_id` = ' . $this->id)
            ->andWhere('`category`.`id` = `service`.`category_id`')
//            ->andWhere('`category`.`id`= 5')
            ->orderBy(new Expression('RAND()'))
            ->all();
        return $select;
    }

    public function getAllAmenities()
    {
        return Service::find()->where(['category_id' => 5])->all();
    }

    public function getRatings()
    {
        $get_ratings = Ratings::find()->asArray()->where(['place_id' => $this->id])->all();
        $ratings = ArrayHelper::map($get_ratings, 'id', 'ratings');
        $ratingsSum = array_sum($ratings);
        $ratingsCount = count($ratings);

        if ($ratingsCount) {
            $averageRating = $ratingsSum / $ratingsCount;
        } else {
            $averageRating = 0;
        }
        return round($averageRating);
    }

    public function getRatingStars()
    {
        $ratings = $this->getRatings();

        if ($ratings == 1) {
            $star = '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
        } elseif ($ratings == 2) {
            $star = '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
        } elseif ($ratings == 3) {
            $star = '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
        } elseif ($ratings == 4) {
            $star = '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
        } elseif ($ratings == 5) {
            $star = '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
            $star .= '<i class="fa fa-star ratings"></i>';
        } else {
            $star = '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
            $star .= '<i class="fa fa fa-star-o ratings"></i>';
        }
        return $star;
    }

    public function getViews()
    {
        $view = Views::findOne(['place_id' => $this->id, 'status' => Yii::$app->params['active']]);
        return (!empty($view)) ? $view->views : 0;
    }

    public function getCategoryName()
    {
        $model = new Service();
        return $model->getCategoryName();
    }

    public function getCategorySlug()
    {

        $model = new Service();
        return $model->getCategorySlug();
    }

    public function getServiceName()
    {
        $model = new Service();
        return $model->getServiceName();
    }

    public function getServiceSlug()
    {
        $model = new Service();
        return $model->getServiceSlug();
    }


    public function getThisPlaceServiceIds()
    {
        return PlaceService::find()->where(['place_id' => $this->id])->all();
    }

    public function getRelatedPlaceIds1()
    {
        $place_ids = array();
        $service_ids = $this->getThisPlaceServiceIds();

        if (!empty($service_ids)):

            foreach ($service_ids as $service_id):

                $place_ids[] = PlaceService::find()->where(['service_id' => $service_id->service_id])->all();

            endforeach;
        endif;

        return $place_ids;
    }

    public function getRelatedPlaceIds()
    {

        $service_ids = (new Query())
            ->select('`service_id`')
            ->from('`place_service`')
            ->where(['place_id' => $this->id])
            ->all();

        $place_ids = (new Query())
            ->select(new Expression('DISTINCT `place_id`'))
            ->from('`place_service`')
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

        Yii::warning('place_ids' . print_r($place_ids, true));

        return self::find()
            ->where(['in', 'id', $place_ids])
            ->andWhere(['status' => Yii::$app->params['active']])
            ->orderBy(new Expression('RAND()'))
            ->limit(4)
            ->all();
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
