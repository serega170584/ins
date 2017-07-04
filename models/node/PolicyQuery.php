<?php

namespace app\models\node;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Policy]].
 *
 * @see Policy
 */
class PolicyQuery extends NodeQuery
{
    public function prepare($builder)
    {
        $this->byType(Policy::getNodeType());
        return parent::prepare($builder);
    }

    /**
     * @param int $type_id
     * @return $this
     */
    public function byDataType($type_id){

        return $this->joinWith(['info' => function(ActiveQuery $q) use($type_id){
            $q->where(['policy_type_id' => $type_id]);
        }]);
    }
}
