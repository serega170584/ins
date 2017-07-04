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
class News extends Node
{

    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent[] = [
            'class' => '\app\components\FileBehavior',
            'file_relation_name' => 'previewPicture',
            'file_extensions' => 'png, jpg',
        ];
        $parent[] = [
            'class' => '\app\components\FileBehavior',
            'file_relation_name' => 'detailPicture',
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
        $return['previewPictureNew'] = 'Изображение анонса';
        $return['previewPictureDelete'] = 'Удалить изображение анонса';
        $return['detailPictureNew'] = 'Детальное изображение';
        $return['detailPictureDelete'] = 'Удалить детальное изображение';
        $return['tagsList'] = 'Теги статьи';
        $return['text_1'] = 'Дата публикации';
        $return['rating'] = 'Компания'; 
        return $return;
    }

    /**
     * Возвращает тип контента для данной модели
     * @return \app\model\node
     */
    public static function getNodeType()
    {
        $return = Type::find()->byCode('news')->one();
        if (!$return) throw new \Exception('Type not found');
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->type_id = self::getNodeType()->id;
        if ($this->isNewRecord) {
            $this->text_1 = date("d/m/Y");
            $this->alias = date("d_m_y");
        }
        return parent::init();
    }


    public function afterFind()
    {
        parent::afterFind();

        $dt = new \DateTime($this->created_at);
        $this->text_1 = $dt->format("d/m/Y");
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->type_id = self::getNodeType()->id;
        return parent::beforeSave($insert);
    }

    public function beforeValidate()
    {
        $this->lang_id = 1;

        if (!trim($this->text_1)) {
            $this->text_1 = date("d/m/Y");
        }

        $dt = \DateTime::createFromFormat("d/m/Y", $this->text_1);
        $this->created_at = $dt->format("Y-m-d 00:00:00");

        if (empty($this->alias)) {
            $this->alias = $dt ? $dt->format('d_m_y') : ($this->id ?: uniqid(""));
        }

        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }

    public function getPreviewThumbUrl(){
        return $this->previewPicture ?
            Yii::$app->imager->getThumbUrl($this->previewPicture->fsPath, 700, 528) : null;
    }

    public function getDetailThumbUrl(){
        return $this->detailPicture ?
            Yii::$app->imager->getThumbUrl($this->detailPicture->fsPath, 700, 528) : null;
    }

}
