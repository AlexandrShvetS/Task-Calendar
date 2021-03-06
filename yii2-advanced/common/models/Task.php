<?php
namespace common\models;

use yii;
use frontend\models\User;

/**
 * Task model
 *
 * @property integer $id
 * @property string $name
 * @property string $info
 * @property string $date_end
 * @property integer $user_id
 */


class Task extends yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    public function fields(){
        return [
            'name',
            'info',
            'date_end',
        ];
    }

    public function extraFields(){
        return [
            'user_id'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'string', 'max' => 250],
            ['info', 'string', 'max' => 1000],
            //[['date_end'], 'required'],
            [['date_end'], 'safe'],
            [['date_end'], 'date', 'format' => 'php:Y-m-d'],
            ['user_id', 'integer'],
        ];
    }

    public function getUser(){
        return $this->hasMany(User::class,['id'=>'user_id']);
        //return $this->hasMany(Product::class,['id'=>'product_id'])->viaTable('product_in_order',['order_id'=>'id']);
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    

    
}
