<?php

namespace app\models\node;

use app\components\iSeo;
use app\models\category\Category;
use app\models\category\Category2;
use app\models\category\CategoryQuery;
use app\models\category\CategoryVideo;
use app\models\category\CategoryVideoQuery;
use dektrium\user\models\User;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\behaviors\TimestampBehavior;
use app\models\lang\Lang;
use app\models\file\File;
use Yii;
use yii\helpers\ArrayHelper;
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
 * @property string $link
 * @property string $alias
 * @property string $short_text
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
 * @property integer $rating
 * @property string $price_from
 *
 * @property integer $parent_id
 *
 * @property Category $category
 * @property Category[] $categories
 * @property Category2 $category2
 * @property CategoryVideo[] $category_video
 *
 * @mixin NestedSetsBehavior
 *
 */
class Node extends \yii\db\ActiveRecord implements iSeo
{
    public $parent_id = 0;
    public $category_id;
    private $category_ids = null;
    private $category_video_ids = null;
    public $category2_id;

    public $text_1;
    public $text_2;
    public $text_3;
    public $text_4;
    public $text_5;
    public $text_6;
    public $text_7;
    public $text_8;
    public $text_9;
    public $text_10;
    public $dataall;



    public function behaviors() {
        return [];
        /*return [
            [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ]
        ];*/
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

	/**
	 * Тип контента
	 * @return \app\models\node\Type|null
	 */
	public function getType()
	{
		return $this->hasOne(Type::className(), ['id' => 'type_id']);
	}


	/**
	 * Язык контента
	 * @return \app\models\lang\Lang|null
	 */
	public function getLang()
	{
		return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
	}

    /**
     * Relation with User Model
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){

        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Relation with Category
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory(){

        return $this->hasOne(Category::className(), ['id' => 'category_id'])
            ->viaTable('{{%sbl_node_to_category}}', ['node_id' => 'id']);
    }

    /**
     * Relation with Category
     * @return \yii\db\ActiveQuery
     */
    public function getCategories(){

        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable('{{%sbl_node_to_category}}', ['node_id' => 'id']);
    }

    /**
     * Relation with Category2
     * @return \yii\db\ActiveQuery
     */
    public function getCategory2(){

        return $this->hasOne(Category2::className(), ['id' => 'category_id'])
            ->viaTable('{{%sbl_node_to_category_2}}', ['node_id' => 'id']);
    }

    /**
     * Relation with CategoryVideo
     * @return \yii\db\ActiveQuery|CategoryVideoQuery
     */
    public function getCategoryVideo()
    {

        return $this->hasOne(CategoryVideo::className(), ['id' => 'category_id'])
            ->viaTable('{{%sbl_node_to_category_video}}', ['node_id' => 'id']);
    }

    /**
     * @return array|int[]
     */
    public function getCategoryVideoIds()
    {
        return ArrayHelper::getColumn($this->getCategoryVideo()->all(), 'id');
    }

    /**
     * @param int[] $ids
     */
    public function setCategoryVideoIds($ids)
    {
        $this->category_video_ids = $ids;
    }

    public function getCategory_ids()
    {
        if (is_null($this->category_ids)) {
            $this->category_ids = [];
            $categories = $this->categories;
            foreach ($categories as $category) {
                $this->category_ids[] = $category->id;
            }
        }
        return $this->category_ids;
    }

    public function setCategory_ids($ids)
    {
        $this->category_ids = $ids;
    }

    /**
     * @inheritdoc
     */
    /*public function afterValidate(){

        if($this->isNewRecord && !$this->parent_id){ //Есди родительский раздел не выбран, устанавливаем текущий корнем
            $this->makeRoot();
        }elseif($this->isNewRecord && $this->parent_id > 0){ //Есди родительский раздел выбран, вставляем текущий в него
            $node = static::findOne($this->parent_id);

            if($node){
                $this->appendTo($node);
            }
        }

        parent::afterValidate();
    }*/


	/**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            //отмечаем пользователя, который создал запись, если можем
            if ($this->isNewRecord && Yii::$app->user && !Yii::$app->user->isGuest) {
                $this->user_id = Yii::$app->user->id;
            }
            //вместо timestamp, быстрее будет
            $date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
            if ($this->isNewRecord) $this->created_at = $date;
            $this->updated_at = $date;

            return true;
        }

        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        //свзяываем с категорией
        if ($this->category_id) {
            $this->category_ids = [$this->category_id];
        }
        if (is_array($this->category_ids)) {
            if ($this->category_id) {
                $this->category_ids[] = $this->category_id;
            }
            $category_ids = array_flip($this->category_ids);

            $categories = $this->categories;
            foreach ($categories as $category) {
                if (array_key_exists($category->id, $category_ids)) {
                    unset($category_ids[$category->id]);
                    continue;
                }
                $this->unlink('categories', $category);
            }

            foreach ($category_ids as $category_id=>$val) {
                $category = Category::findOne($category_id);
                if ($category) {
                    $this->link('categories', $category);
                }
            }
        }

        //свзяываем с категорией2
        if ($this->category2 && $this->category2->id != $this->category2_id) {
            $this->unlink('category2', $this->category2, true);

            if ($this->category2_id) {
                $category2 = Category2::findOne($this->category2_id);
                if ($category2) {
                    $this->link('category2', $category2);
                }
            }
        }

        if (!$this->category2 && $this->category2_id) {
            $category2 = Category2::findOne($this->category2_id);
            if ($category2) {
                $this->link('category2', $category2);
            }
        }

        if ($this->category_video_ids === "") {
            $this->category_video_ids = [];
        }
        if (is_array($this->category_video_ids)) {
            $currentIds = $this->getCategoryVideoIds();
            $currentRels = $this->getCategoryVideo()->indexBy('id')->all();
            $idToDelete = array_diff($currentIds, $this->category_video_ids);
            $idToAdd = array_diff($this->category_video_ids, $currentIds);

            foreach ($idToDelete as $id) {
                if (isset($currentRels[$id])) {
                    $this->unlink('categoryVideo', $currentRels[$id], true);
                }
            }

            if ($idToAdd) {
                $newRels = CategoryVideo::find()->andWhere(['id' => $idToAdd])->indexBy('id')->all();
                foreach ($newRels as $newRel) {
                    $this->link('categoryVideo', $newRel);
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['text_1', 'text_2', 'text_3', 'text_4', 'text_5', 'text_6', 'text_7', 'text_8', 'text_9', 'text_10'], 'string'],

            //цифры приводим к цифрам
            [
                [
                    'sort',
                    'status',
                    'rating',
                    'type_id',
                    'lang_id',
                    'parent_id',
                    'category_id',
                    'category2_id',
                    'price_from',
                ],
                'filter',
                'filter' => 'intval'
            ],

            [['category_ids', 'categoryVideoIds'], 'safe'],

            //убираем лишние пробелы в начале и конце строки
            [
                [
                    'title',
                    'link',
                    'link2',
                    'alias',
                    'short_text',
                    'preview_text',
                    'detail_text',
                    'seo_title',
                    'seo_keywords',
                    'seo_description',
                ],
                'trim'
            ],

            //убираем теги оттуда, где их не должно быть
            [
                [
                    'title',
                    'alias',
                    'seo_title',
                    'seo_keywords',
                    'seo_description',
                ],
                'filter',
                'filter' => 'strip_tags'
            ],

            //обязательные поля
            [['title', 'alias', 'type_id', 'lang_id'], 'required'],

            //проверяем, чтобы были связанные элементы
            [
                'type_id',
                'exist',
                'targetClass' => '\app\models\node\Type',
                'targetAttribute' => 'id'
            ],
            [
                'lang_id',
                'exist',
                'targetClass' => '\app\models\lang\Lang',
                'targetAttribute' => 'id'
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    { 
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'sort' => 'Сортировка',
            'status' => 'Отображать',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'link2' => 'Ссылка2',
            'alias' => 'Код чпу',
            'short_text' => 'Текст',
            'preview_text' => 'Текст анонса',
            'detail_text' => 'Текст страницы',
            'price_from' => 'Цена, от',
            'seo_title' => 'SEO Заголовок',
            'seo_keywords' => 'SEO Keywords',
            'seo_description' => 'SEO Description',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'lang_id' => 'Язык',
            'rating' => 'Рейтинг',

            'parent_id' => 'Родительский раздел',
            'category_id' => 'Категория',
            'category2_id' => 'Категория 2ур',

            'dataall' => 'Данные',
            'text_1' => 'Текст 1',
            'text_2' => 'Текст 2',
            'text_3' => 'Текст 3',
            'text_4' => 'Текст 4',
            'text_5' => 'Текст 5',
        ];
    }

    /**
     * @inheritdoc
     * @return NodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NodeQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sbl_node}}';
    }

    /**
     * Return SEO title
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seo_title ? $this->seo_title : $this->title;
    }

    /**
     * Return keywords for meta tag
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seo_keywords;
    }

    /**
     * Return description for meta tag
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seo_description;
    }

    public function getUrl(){
        return Url::to(['/node/view', 'id' => $this->id]);
    }

    /**
     * Fields allow in API JSON
     * @return array
     */
    public function fields(){

        return [
            'id',
            'title',
            'link',
            'link2',
            'status',
            'alias',
            'url',
            'created_at',
            'preview_text',
            'type',
            'price_from'
//            'detail_text',
            /*'previewThumbUrl',
            'detailThumbUrl'*/
        ];
    }

    public function extraFields(){

        return [
            'type'
        ];
    }

    public static function getCompany() {
        return [
            '0' => 'КОМПАНИЯ 1',
            '1' => 'КОМПАНИЯ 2',
        ];
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return (static::getCompany())[$this->rating];
    }
}
