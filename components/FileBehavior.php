<?php

namespace app\components;

use app\models\node\Node;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\validators\Validator;
use yii\web\UploadedFile;

/**
 * @property Node $owner
 */
class FileBehavior extends Behavior
{
    /**
     * @var string
     */
    public $file_class = '\app\models\file\File';
    /**
     * @var string
     */
    public $file_junction = '{{%sbl_file_to_entity}}';
    /**
     * @var string
     */
    public $file_relation_name = null;
    /**
     * @var string
     */
    public $file_extensions = 'png, jpg';
    /**
     * @var bool
     */
    public $file_skip_on_empty = true;
    /**
     * @var mixed
     */
    protected $_attributes = [
        'new' => null,
        'delete' => 0,
    ];



    /**
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if ($name === $this->getRelationFunctionName()) {
            return true;
        } else {
            return parent::hasMethod($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if ($name === $this->getRelationFunctionName()) {
            return $this->returnRelation();
        } else {
            return parent::__call($name, $params);
        }
    }



    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if ($name === $this->file_relation_name || $this->isAttribute($name)) {
            return true;
        } else {
            return parent::canGetProperty($name, $checkVars);
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name === $this->file_relation_name) {
            return $this->returnRelation();
        } elseif ($aname = $this->isAttribute($name)) {
            return $this->_attributes[$aname];
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if ($this->isAttribute($name)) {
            return true;
        } else {
            return parent::canSetProperty($name, $checkVars);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($aname = $this->isAttribute($name)) {
            $this->_attributes[$aname] = $value;
        } else {
            return parent::__set($name, $value);
        }
    }



    /**
     * Возвращает название функции relation'а
     * @return string
     */
    protected function getRelationFunctionName()
    {
        return 'get' . $this->file_relation_name;
    }

    /**
     * Возвращает название для сущности в таблице связей
     * @return string
     */
    protected function getEntityName()
    {
        return get_class($this->owner) . '_' . $this->file_relation_name;
    }

    /**
     * Проверяет является ли заданное имя аттрибутом
     * @param string $name
     * @return string|bool
     */
    protected function isAttribute($name)
    {
        $names = array_keys($this->_attributes);
        foreach ($names as $aname) {
            if ($this->getAttributeName($aname) !== $name) continue;
            return $aname;
        }
        return false;
    }

    /**
     * Возвращает название атрибута
     * @param string $name
     * @return string
     */
    protected function getAttributeName($name)
    {
        return $this->file_relation_name . ucfirst($name);
    }

    /**
     * Возвращает описание relation для модели
     */
    protected function returnRelation()
    {
        $ar = $this->owner;
        $pk = $ar->primaryKey();
        $pk = reset($pk);
        return $ar
            ->hasOne(
                $this->file_class,
                ['id' => 'file_id']
            )->viaTable(
                $this->file_junction,
                ['entity_id' => $pk],
                function ($query) {
                    $query->andWhere(['entity_name' => $this->getEntityName()]);
                }
            );
    }



    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        //валидатор для поля с файлом
        $fileValidator = Validator::createValidator(
            'file',
            $this->owner,
            $this->getAttributeName('new'),
            [
                'extensions' => $this->file_extensions,
                'skipOnEmpty' => $this->file_skip_on_empty,
            ]
        );
        $owner->validators[] = $fileValidator;
        //валидатор для поля удаления файла
        $deleteFileValidator = Validator::createValidator(
            'safe',
            $this->owner,
            $this->getAttributeName('delete')
        );
        $owner->validators[] = $deleteFileValidator;
    }

    /**
     * Перед валидацией добавляем валидатор для файла
     */
    public function beforeValidate($event)
    {
        //пробуем найти загруженные файлы
        $file = UploadedFile::getInstance($this->owner, $this->getAttributeName('new'));
        if ($file) $this->_attributes['new'] = $file;
    }

    /**
     * После сохранения основной модели, пробуем сохранить и файл
     */
    public function afterSave($event)
    {
        $relation = $this->file_relation_name;
        $relatedModel = $this->owner->$relation;
        $is_delete = (int) $this->_attributes['delete'];
        if ($is_delete && $relatedModel) {
            $this->owner->unlink($relation, $relatedModel, true);
            $relatedModel->delete();
        } elseif (!$is_delete && $this->_attributes['new']) {
            $class = $this->file_class;
            $file = $relatedModel === null ? new $class : $relatedModel;
            $file->loadFile($this->_attributes['new']);
            $file->save();
            if (!$relatedModel) {
                $this->owner->link(
                    $relation,
                    $file,
                    ['entity_name' => $this->getEntityName()]
                );
            }
        }
    }

    /**
     * Перед удалением удаляем связанную модель
     */
    public function beforeDelete($event)
    {
        $relation = $this->file_relation_name;
        $relatedModel = $this->owner->$relation;
        if ($relatedModel) {
            $this->owner->unlink($relation, $relatedModel, true);
            $relatedModel->delete();
        }
    }
}
