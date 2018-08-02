<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;


class TripSearch extends \app\models\Trip
{
    const CORPORATE_ID = 3; // trip.coprorate_id
    const SERVICE_ID = 2; // trip_service.service_id
    const TRIP_IDS_KEY = 'trip_ids_';

    /**
     * @param $airport_name
     * @return null|ActiveDataProvider
     */
    public static function getTrips($airport_name)
    {
        $cache = Yii::$app->cache; // Redis

        // find and remember for the future departure airport:
        $ap_key = 'AP_' . Inflector::slug($airport_name);
        $airport_id = $cache->get($ap_key);
        if ($airport_id === false) {
            $airport_rec = AirportName::find()->where(['value' => $airport_name])->one();
            if (!empty($airport_rec)) {
                $airport_id = $airport_rec->airport_id;
                $cache->set($ap_key, $airport_id, 3600);
            } else {
                return null;
            }
        }

        // найдём все  `trip_service`.`trip_id` для этого аэропорта вылета:
        $trip_ids = $cache->get(self::TRIP_IDS_KEY . $airport_id);
        if ($trip_ids === false) {
            $segment_models = FlightSegment::find()->where(['depAirportId' => $airport_id])->select('flight_id')->all();
            $segments = [];
            foreach ($segment_models as $segment_model) {
                $segments[] = $segment_model->flight_id;
            }

            $trip_services = TripService::find()->where(['service_id' => self::SERVICE_ID])
                ->andWhere('`id` IN (' . implode(',', $segments) . ')')
                ->select('trip_id')->all();
            $trip_ids = [];
            foreach ($trip_services as $trip_service) {
                $trip_ids[] = $trip_service->trip_id;
            }
            $cache->set(self::TRIP_IDS_KEY . $airport_id, $trip_ids, 90);
        }

        if(empty($trip_ids)) return null;

        $query = self::find()->where('`id` IN (' . implode(',', $trip_ids) . ')') ->cache(60);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $provider;
    }


}
