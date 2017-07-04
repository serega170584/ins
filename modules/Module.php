<?php

namespace app\modules\admin;

use Yii;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public $layout = 'main';

    public function init()
	{
        Yii::$app->errorHandler->errorAction = '/admin/default/error';
        Yii::$app->user->loginUrl = ['user/login'];
		parent::init();
	}
}
