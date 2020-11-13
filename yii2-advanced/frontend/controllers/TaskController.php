<?php

namespace frontend\controllers;
use Yii;
use common\models\Task;
use yii\data\ActiveDataProvider;

//use frontend\models\rest\Order;


class TaskController extends BaseApiController
{
    public $modelClass = Task::class;

    //Переопределить класс и получать только те задания, которые пользователя.

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = function ($action) {
            return new ActiveDataProvider([
                'query' => Task::find()->where('user_id=:user_id', ['user_id' => Yii::$app->user->identity->id]),
            ]);
        };

        return $actions;
    }
}