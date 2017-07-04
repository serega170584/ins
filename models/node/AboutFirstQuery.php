<?php

namespace app\models\node;

class AboutFirstQuery extends NodeQuery
{
    public function prepare($builder)
    {
        $this->byType(AboutFirst::getNodeType());
        return parent::prepare($builder);
    }
}
