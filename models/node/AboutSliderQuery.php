<?php

namespace app\models\node;

class AboutSliderQuery extends NodeQuery
{
    public function prepare($builder)
    {
        $this->byType(AboutSlider::getNodeType());
        return parent::prepare($builder);
    }
}
