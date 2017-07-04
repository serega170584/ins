<?php

namespace app\models\node;

/**
 * This is the ActiveQuery class for [[News]].
 *
 * @see News
 */
class AboutQuery extends NodeQuery
{
    public function prepare($builder)
    {
        $this->byType(About::getNodeType());
        return parent::prepare($builder);
    }
}
