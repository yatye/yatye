<?php
/**
 * Created by PhpStorm.
 * User: ntezi
 * Date: 12/06/2016
 * Time: 17:56
 */

namespace backend\components;

use Yii;
use yii\helpers\Url;
use common\models\District;
use backend\models\place\Place;
use common\helpers\RecordHelpers;
use yii\web\NotFoundHttpException;
use backend\models\place\UserPlace;
use backend\models\place\Subscription;
use backend\models\place\PlaceHasAnother;

class AdminController extends BaseController
{
    public function actionSetBasicInfo()
    {
        $model = $this->findModel(Yii::$app->request->get('id'));
        $model->scenario = 'update';
        $url = Url::to(['settings/', 'id' => $model->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash("success", Yii::t('app', 'Basic info successfully added!'));
            return $this->redirect($url);
        } else {
            Yii::$app->getSession()->setFlash("fail", Yii::t('app', 'Basic info is not added!'));
            return $this->redirect($url);
        }
    }

    public function actionSetLocation()
    {
        $model = $this->findModel(Yii::$app->request->get('id'));
        $url = Url::to(['settings/', 'id' => $model->id]);

        if ($model) {
            if ($model->load(Yii::$app->request->post())) {
                $POST_VARIABLE = Yii::$app->request->post('Place');
                $district = District::findOne($POST_VARIABLE['district_id']);
                $model->province_id = $district->province_id;
                $model->save(0);

                Yii::$app->getSession()->setFlash("success", Yii::t('app', 'Location successfully added!'));
                return $this->redirect($url);
            } else {

                Yii::$app->getSession()->setFlash("fail", Yii::t('app', 'Location is not added!'));
                return $this->redirect($url);
            }
        }
    }

    public function actionSetSettings()
    {
        $place_id = Yii::$app->request->get('place_id');
        $model = $this->findModel($place_id);
        $url = Url::to(['settings/', 'id' => $place_id]);

        $posts = Yii::$app->request->post('Place');

        if ($model->load(Yii::$app->request->post())) {
            $model->save(0) ;

            if ($posts['status'] == 1) {
                $emails = $model->getContact(Yii::$app->params['EMAIL']);
                foreach ($emails as $email) :
                    Subscription::savePlaceToSubscription($email->name, $place_id);
                endforeach;
            }

            Yii::$app->getSession()->setFlash("success", Yii::t('app', 'Settings successfully added.'));
            return $this->redirect($url);
        } else {

            Yii::$app->getSession()->setFlash("fail", Yii::t('app', 'Settings are not added!'));
            return $this->redirect($url);
        }
    }

    public function actionSetUsers()
    {
        $place_id = Yii::$app->request->get('place_id');
        $POST_VARIABLE = Yii::$app->request->post('UserPlace');
        $url = Url::to(['settings/', 'id' => $place_id]);

        if (Yii::$app->request->isPost) {
            foreach ($POST_VARIABLE['user_id'] as $key => $user_id) {
                $model = new UserPlace();
                RecordHelpers::saveModelHasData($model, 'place_id', 'user_id', $place_id, $user_id);
            }

            Yii::$app->getSession()->setFlash("success", Yii::t('app', 'Users successfully added.'));
            return $this->redirect($url);
        } else {
            Yii::$app->getSession()->setFlash("fail", Yii::t('app', 'Users are not added!'));
            return $this->redirect($url);
        }
    }

    public function actionOtherPlaces()
    {
        $place_id = Yii::$app->request->get('place_id');
        $POST_VARIABLE = Yii::$app->request->post('PlaceHasAnother');
        $url = Yii::$app->request->baseUrl . '/settings/?id=' . $place_id;

        if (Yii::$app->request->isPost) {
            foreach ($POST_VARIABLE['other_place_id'] as $key => $other_place_id) {
                $model = new PlaceHasAnother();
                RecordHelpers::saveModelHasData($model, 'place_id', 'other_place_id', $place_id, $other_place_id);
            }

            Yii::$app->getSession()->setFlash("success", Yii::t('app', 'Other places successfully added.'));
            return $this->redirect($url);
        } else {
            Yii::$app->getSession()->setFlash("fail", Yii::t('app', 'Other places are not added.'));
            return $this->redirect($url);
        }

    }

    /**
     * Finds the Place model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Place the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Place::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}