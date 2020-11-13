<?php
namespace frontend\models\rest;

use Yii;
use yii\helpers\Url;
use yii\web\Linkable;
use yii\filters\AccessControl;

class Task extends \common\models\Task implements Linkable
{
    public function fields(){
        return parent::fields();
    }

    public function extraFields(){
        return [
            'user'
        ];
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }
    public function getLinks()
    {
        return [
            'single_link' => Url::to(['task/view', 'id' => $this->id], true),
        ];
    }

}