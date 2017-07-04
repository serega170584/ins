<?php
namespace app\models\forms;


use yii\base\Model;

class EventForm extends Model {

    public $subject;
    public $product;
    public $policy_title;
    public $number;
    public $phone;
    public $email;
    public $email_to;
    public $name;
    public $message;

    public function rules(){

        return [
            [['subject', 'product', 'number', 'phone', 'email', 'name', 'message', 'policy_title', 'email_to'], 'safe'],
        ];
    }

    public function send(){

        $email_to = \Yii::$app->params['email_to'];
        if (!empty($this->email_to)) {
            $email_to = $this->email_to;
        }

        $res = \Yii::$app->mailer->compose('event', [
            'form' => $this
        ])
            ->setFrom(\Yii::$app->params['email_from'])
            ->setTo($email_to)
            ->setBcc(\Yii::$app->params['email_bcc'])
            ->setSubject('Сообщение о страховом случае на сайте www.sberins.ru')
            ->send();

        return $res;
    }

    public function formName(){
        return '';
    }
}