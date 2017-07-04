<?php

namespace app\models\node;

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
 */
class About extends Node
{
    //private $arrdata = array();


    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent[] = [
            'class' => '\app\components\FileBehavior',
            'file_relation_name' => 'previewPicture',
            'file_extensions' => 'png, jpg',
        ];
        $parent[] = [
            'class' => '\app\components\TagBehavior',
            'tag_relation_name' => 'tags'
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
        return $return;
    }

    /**
     * Возвращает тип контента для данной модели
     * @return \app\model\node
     */
    public static function getNodeType()
    {
        $return = Type::find()->byCode('about')->one();
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

/*
    public function __set($property, $value)
    {
        if(strripos($property, 'text_') === false) {

        } else {
//            vd($property);
            //$this->arrdata[$property] = $value;
            //$this->detail_text = serialize($this->arrdata);
        }
        parent::__set($property, $value);
    }


    public function __get($property)
    {
        if(strripos($property, 'tv') === false) {
            return parent::__get($property);
        } else {

            $this->arrdata = unserialize($this->detail_text);

            return $this->arrdata[$property];

//            if(in_array($property, $this->arrdata)) {
//                return $this->arrdata[$property];
//            } else {
//                return '';
//            }
        }
    }
    */

    public function beforeValidate()
    {
        $this->lang_id = 1;
        $this->alias = 'about_contact';
        $this->rating = Yii::$app->request->get('company', 0);
        return parent::beforeValidate();
    }


    public function beforeSave($insert)
    {
        $this->type_id = self::getNodeType()->id;

            $this->dataall = [
                't_1' => $this->text_1,
                't_2' => $this->text_2,
                't_3' => $this->text_3,
                't_4' => $this->text_4,
                't_5' => $this->text_5,
                't_6' => $this->text_6,
                't_7' => $this->text_7,
                't_8' => $this->text_8,
                't_9' => $this->text_9,
                't_10' => $this->text_10,
            ];

            $this->detail_text = serialize($this->dataall);

        return parent::beforeSave($insert);
/*
        if (parent::beforeSave($insert)) {
            $this->type_id = self::getNodeType()->id;
            return true;
        } else {
            return false;
        }
*/
    }


    public function afterSave($insert, $changedAttributes)
    {
        $this->dataall = unserialize($this->detail_text);

        $this->text_1 = @$this->dataall['t_1'];
        $this->text_2 = @$this->dataall['t_2'];
        $this->text_3 = @$this->dataall['t_3'];
        $this->text_4 = @$this->dataall['t_4'];
        $this->text_5 = @$this->dataall['t_5'];
        $this->text_6 = @$this->dataall['t_6'];
        $this->text_7 = @$this->dataall['t_7'];
        $this->text_8 = @$this->dataall['t_8'];
        $this->text_9 = @$this->dataall['t_9'];
        $this->text_10 = @$this->dataall['t_10'];

        parent::afterSave($insert, $changedAttributes);
    }




    public function afterFind()
    {
        parent::afterFind();

        $this->dataall = unserialize($this->detail_text);

        $this->text_1 = @$this->dataall['t_1'];
        $this->text_2 = @$this->dataall['t_2'];
        $this->text_3 = @$this->dataall['t_3'];
        $this->text_4 = @$this->dataall['t_4'];
        $this->text_5 = @$this->dataall['t_5'];
        $this->text_6 = @$this->dataall['t_6'];
        $this->text_7 = @$this->dataall['t_7'];
        $this->text_8 = @$this->dataall['t_8'];
        $this->text_9 = @$this->dataall['t_9'];
        $this->text_10 = @$this->dataall['t_10'];

    }








    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new AboutQuery(get_called_class());
    }

    public function getPreviewThumbUrl(){
        return $this->previewPicture ?
            Yii::$app->imager->getThumbUrl($this->previewPicture->fsPath, 80, 80) : null;
    }

}
