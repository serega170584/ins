<?php
namespace app\models\forms;


use app\models\node\Career;
use yii\base\Model;
use yii\base\Exception;
use yii\web\UploadedFile;

class AboutResumeForm extends Model
{
    public $subject;
    public $name;
    public $email;
    public $title;

    public $message;

    public function rules()
    {
        return [
            [['name', 'email', 'title', 'message', 'subject'], 'safe'],
        ];
    }

    public function send()
    {
        $career = Career::find()->byId($this->title)->one();
        $detail_text = unserialize($career->detail_text);
        $this->title = $career->title;
        $email_to = \Yii::$app->params['email_to'];
        if (!empty($detail_text['t_4'])) {
            $email_to = $detail_text['t_4'];
        }

        $res = \Yii::$app->mailer->compose('resume', [
            'form' => $this
        ])
            ->setFrom(\Yii::$app->params['email_from'])
            ->setTo($email_to)
            ->setBcc(\Yii::$app->params['email_bcc'])
            ->setSubject('Резюме с сайта');

        $file = UploadedFile::getInstanceByName('file');
        if (!$file->getHasError()) {
            $res->attach($file->tempName, ['fileName' => $file->name]);
        }

        $res->send();

        return $res;
    }

    public function formName()
    {
        return '';
    }
}