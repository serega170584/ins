<?php

namespace app\models\node;

class AboutHistoryQuery extends NodeQuery
{
    public function prepare($builder)
    {
        $this->byType(AboutHistory::getNodeType());
        return parent::prepare($builder);
    }
}
