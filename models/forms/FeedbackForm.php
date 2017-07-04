<?php
namespace app\models\forms;


use yii\base\Model;

class FeedbackForm extends Model {

    public $subject;
    public $name;
    public $phone;
    public $time;
    public $email;
    public $preferred;
    public $region;
    public $title;
    public $message;

    public function rules(){

        return [
            [['name', 'phone', 'time', 'email', 'preferred', 'region', 'title', 'message', 'subject'], 'safe'],
        ];
    }

    public function send(){
        $res = \Yii::$app->mailer->compose('feedback', [
            'form' => $this
        ])
            ->setFrom(\Yii::$app->params['email_from'])
            ->setTo(\Yii::$app->params['email_feedback'])
            ->setBcc(\Yii::$app->params['email_bcc'])
            ->setSubject('Форма обратной связи на сайте www.sberins.ru')
            ->send();

        return $res;
    }

    public function formName(){
        return '';
    }
}