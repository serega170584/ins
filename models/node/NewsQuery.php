<?php

namespace app\models\node;

/**
 * This is the ActiveQuery class for [[News]].
 *
 * @see News
 */
class NewsQuery extends NodeQuery
{
    public function prepare($builder)
    {
        $this->byType(News::getNodeType());
        return parent::prepare($builder);
    }

    /**
     * Сортировка по умолчанию
     * @param string $order
     * @return \app\models\node\NodeQuery
     */
    public function defaultSort($order = null)
    {
        $order = strtolower($order) === 'asc' ? SORT_ASC : SORT_DESC;
        return $this->addOrderBy(['created_at' => $order]);
    }

}
