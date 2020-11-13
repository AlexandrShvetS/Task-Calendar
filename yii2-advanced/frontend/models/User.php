<?php
namespace frontend\models;


use common\models\Task;

class User extends \common\models\User
{

	public function fields()
	{
		return [
			'id',
			'username',
			'email',
			'task',
			/*'is_rrr' => function($model){
				return $model->id == 1 ? 'rrr': 'ddd';
			}*/
		];
	}

	public function extraFields()
	{
		return [
			'task'
		];
	}

	public function getTask(){
		return $this->hasMany(Task::class,['user_id'=>'id']);
	}

	public function getMama(){
		return $this->id . 'fff';
	}
}