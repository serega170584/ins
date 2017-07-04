<?php
namespace app\models\forms;


use yii\base\Model;

class OrderForm extends Model {

    public $subject;
    public $name;
    public $phone;
    public $policy_title;
    public $email;
    public $email_to;

    public function rules(){

        return [
            [['name', 'phone', 'subject', 'email', 'policy_title', 'email_to'], 'safe'],
        ];
    }

    public function send(){

        $email_to = \Yii::$app->params['email_to'];
        if (!empty($this->email_to)) {
            $email_to = $this->email_to;
        }

        $res = \Yii::$app->mailer->compose('order', [
            'form' => $this
        ])
            ->setFrom(\Yii::$app->params['email_from'])
            ->setTo($email_to)
            ->setBcc(\Yii::$app->params['email_bcc'])
            ->setSubject('Заказ звонка на сайте www.sberins.ru')
            ->send();

        return $res;
    }

    public function formName(){
        return '';
    }
}