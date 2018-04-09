<?php

class JQ_CustomWidgets_Block_Widget_Recentreviews extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface {

	protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Retrieve recent size (count)
     *
     * @return int
     */
    public function getShowRecentCount()
    {
        if ($this->hasData('recent_size')) {
            return $this->getData('recent_size');
        }
        return 1;
    }

    protected function _toHtml()
    {
        return parent::_toHtml();
    }


}