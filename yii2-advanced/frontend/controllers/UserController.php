<?php

namespace frontend\controllers;

use frontend\models\User;
//use common\models\User;
use yii\rest\ActiveController;

//class UserController extends ActiveController
class UserController extends BaseApiController
{
	public $modelClass = User::class;
	
}
