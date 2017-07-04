<?php

namespace app\models\node;

use app\components\FileBehavior;
use app\modules\admin\components\content_constructor\behaviors\Ar;
use app\modules\admin\components\content_constructor\ConstructorOnlinePolicy;
use app\modules\admin\components\content_constructor\ConstructorPolicy;
use Yii;
use yii\helpers\Url;

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
 * @property PolicyInfo $data
 * @property PolicyInfo $info
 *
 * @mixin FileBehavior
 * @mixin Ar
 */
class Policy extends Node
{
    protected $data_policy;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->type_id = self::getNodeType()->id;
        parent::init();
    }

    /** 
     * @inheritdoc
     */
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
            'file_relation_name' => 'mainImage',
            'file_extensions' => 'png, jpg',
        ];
        $parent['ar'] = [
            'class' => 'app\modules\admin\components\content_constructor\behaviors\Ar',
            'constructor' => 'app\modules\admin\components\content_constructor\ConstructorPolicy'
        ];
        return $parent;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $return = parent::attributeLabels();
        $return['mainImageNew'] = 'Изображение в первом блоке';
        $return['mainImageDelete'] = 'Удалить изображение';
        $return['previewPictureNew'] = 'Изображение в списке продуктов';
        $return['previewPictureDelete'] = 'Удалить изображение для плитки';
        $return['article'] = 'Конструктор полиса';
        $return['short_text'] = 'Текст в плитке';
        $return['rating'] = 'Компания';
        $return['link'] = 'Ссылка оформить полис';
        $return['link2'] = 'Ссылка продлить полис';
        return $return;
    }

    /**
     * Возвращает тип контента для данной модели
     * @throws \Exception
     * @return \app\model\node
     */
    public static function getNodeType()
    {
        $return = Type::find()->byCode('policy')->one();
        if (!$return) throw new \Exception('Type not found');
        return $return;
    }

    /**
     * Тип полиса
     * @param $name
     * @return PolicyType|array|null
     * @throws \Exception
     */
    public static function getPolicyType($name){
        $return = PolicyType::find()->byCode($name)->one();
        if (!$return) throw new \Exception('Policy type not found');
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        $this->lang_id = 1;

        $this->data->node_id = $this->id;

        if(parent::beforeValidate()){
            return $this->data->validate();
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert){

        if(parent::beforeSave($insert)){
            $this->type_id = self::getNodeType()->id;
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes){
        $this->data->node_id = $this->id;
        $this->data->save();

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete(){

        if(parent::beforeDelete()){
            $this->data->delete();
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getMainImageUrl(){
        return $this->mainImage ? $this->mainImage->getUrl() : ($this->previewPicture ? $this->previewPicture->getUrl() : null);
    }

    /**
     * @return string|null
     */
    public function getPreviewImageUrl(){
        return $this->previewPicture ? $this->previewPicture->getUrl() : ($this->mainImage ? $this->mainImage->getUrl() : null);
    }

    /**
     * @return string
     */
    public function getUrl(){
        return Url::to(['policy/view', 'id' => $this->id]);
    }

    /**
     *
     * @return PolicyInfo
     */
    public function getData(){
        if(!$this->data_policy)
            $this->data_policy = $this->info ? $this->info : new PolicyInfo();

        return $this->data_policy;
    }

    /**
     * Relation with policy info
     * @return \yii\db\ActiveQuery
     */
    public function getInfo(){
        return $this->hasOne(PolicyInfo::className(), ['node_id' => 'id']);
    }

    /**
     * Relation with policy type
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyDataType(){

        return $this->hasOne(PolicyType::className(), ['id' => 'policy_type_id'])
            ->viaTable(PolicyInfo::tableName(), ['node_id' => 'id']);
    }


    public function getConstructor(){
        /** @var PolicyType $type */
        //$type = PolicyType::findOne($this->data->policy_type_id);
        $constructor = new ConstructorPolicy();

        return $constructor;
    }

    public function changeConstructor(){
        $this->constructor = $this->getConstructor();
    }

    /**
     * @return PolicyQuery
     */
    public static function find()
    {
        return new PolicyQuery(get_called_class());
    }

    public static function populateRecord($record, $row)
    {
        parent::populateRecord($record, $row);
    }


}
