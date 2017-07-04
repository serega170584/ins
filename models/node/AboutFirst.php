<?php

namespace app\models\node;

use app\modules\admin\components\content_constructor\behaviors\Ar;
use Yii;

/**
 * This is the model class for table "{{%sbl_node}}".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $sort
 * @property integer $status
 * @property string $title
 * @property string $alias
 * @property string $preview_text
 * @property string $detail_text
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $type_id
 * @property integer $user_id
 * @property integer $lang_id
 *
 * @mixin Ar
 */
class AboutFirst extends Node
{
    //private $arrdata = array();


    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent[] = [
            'class' => '\app\components\TagBehavior',
            'tag_relation_name' => 'tags'
        ];
        $parent[] = [
            'class' => 'app\modules\admin\components\content_constructor\behaviors\Ar',
            /*'constructor' => 'app\modules\admin\components\content_constructor\Constructor'*/
        ];
        return $parent;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $return = parent::attributeLabels();
        $return['previewPictureNew'] = 'Фото сотрудника';
        $return['previewPictureDelete'] = 'Удалить изображение анонса';
        $return['tagsList'] = 'Теги статьи';
        $return['rating'] = 'Компания';

        $return['text_1'] = 'Заголовок';
        $return['preview_text'] = 'Текст';
        $return['text_2'] = 'Код видео';
        $return['text_3'] = 'Подпись видео';

        $return['text_5'] = 'Текст';
        $return['article'] = 'Конструктор страницы';
        return $return;
    }

    /**
     * Возвращает тип контента для данной модели
     * @return \app\model\node
     */
    public static function getNodeType()
    {
        $return = Type::find()->byCode('about_f')->one();
        if (!$return) throw new \Exception('Type not found');
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->type_id = self::getNodeType()->id;
        return parent::init();
    }


    public function beforeValidate()
    {
        $this->lang_id = 1;
        $this->sort = 0;
        if (!trim($this->alias)) {
            $this->alias = 'about_first';
        }
        $this->rating = Yii::$app->request->get('company', 0);
        return parent::beforeValidate();
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->type_id = self::getNodeType()->id;
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new AboutFirstQuery(get_called_class());
    }

}
