<?php
/**
 * Created by PhpStorm.
 * User: mariusngaboyamahina
 * Date: 4/9/17
 * Time: 10:17 AM
 */

namespace backend\models;

use Yii;
use common\models\FacebookEvents as BaseFacebookEvents;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class FacebookEvents extends BaseFacebookEvents
{
    public $access_token;
    public $endpoints;

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['event_id', 'name', 'start_time', 'end_time'], 'required'],
            [['event_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['name', 'location', 'city', 'country'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => Yii::$app->params['inactive']],
            [['event_id'], 'unique'],
        ];
    }

    public static function importEvents($events)
    {

        foreach ($events as $event) {
            $event_id = (isset($event['id'])) ? $event['id'] : '-';
            $name = (isset($event['name'])) ? $event['name'] : '-';
            $start_time = (isset($event['start_time'])) ? $event['start_time'] : '-';
            $end_time = (isset($event['end_time'])) ? $event['end_time'] : '-';
            $description = (isset($event['description'])) ? $event['description'] : '-';
            $latitude = (isset($event['place']['location']['latitude'])) ? $event['place']['location']['latitude'] : '-';
            $longitude = (isset($event['place']['location']['longitude'])) ? $event['place']['location']['longitude'] : '-';
            $city = (isset($event['place']['location']['city'])) ? $event['place']['location']['city'] : '-';
            $country = (isset($event['place']['location']['country'])) ? $event['place']['location']['country'] : '-';
            $location = (isset($event['place']['name'])) ? $event['place']['name'] : '-';
            $check = FacebookEvents::findOne(['event_id' => $event_id]);

            if (empty($check)) {
                $facebook_event = new FacebookEvents();
                $facebook_event->event_id = $event_id;
                $facebook_event->name = $name;
                $facebook_event->start_time = $start_time;
                $facebook_event->end_time = $end_time;
                $facebook_event->description = $description;
                $facebook_event->latitude = $latitude;
                $facebook_event->longitude = $longitude;
                $facebook_event->location = $location;
                $facebook_event->city = $city;
                $facebook_event->country = $country;
                $facebook_event->status = Yii::$app->params['inactive'];
                $facebook_event->save(0);
            }
        }
    }

    public function saveFacebookEvents()
    {
        $event = new Event();
        $event->name = $this->name;
        $event->description = $this->description;
        $event->address = $this->location . ', ' . $this->city . ', ' . $this->country;
        $event->start_date = substr($this->start_time, 0, 10);
        $event->end_date = substr($this->end_time, 0, 10);
        $event->start_time = substr($this->start_time, 11, 8);
        $event->end_time = substr($this->end_time, 11, 8);
        $event->latitude = ($this->latitude != 0) ? $this->latitude : null;
        $event->longitude = ($this->longitude != 0) ? $this->longitude : null;
        $event->status = Yii::$app->params['active'];
        if ($event->save()) {
            return true;
        } else {
            return false;
        }
    }
}