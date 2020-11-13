<?php

namespace frontend\controllers;

use common\models\Config;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
//use frontend\models\rest\Order;


class ConfigController extends ActiveController
{
    public $modelClass = Config::class;

    /*public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = function ($action) {
            return new ActiveDataProvider([
                'query' => Task::find()->where('user_id=:user_id', [':user_id' => $_GET['user_id']]),
            ]);
        };

        return $actions;
    }*/

}