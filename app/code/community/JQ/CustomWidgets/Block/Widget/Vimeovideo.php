<?php

class JQ_CustomWidgets_Block_Widget_Vimeovideo extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{

    protected function _construct()
    {
        parent::_construct();
    }

    public function getCode()
    {
        $code = parent::getCode();
        // sanitise user input in case user cannot read instructions
        switch (true) {
            case preg_match('#^(\d{6,12})$#', $code, $match):
            case preg_match('#//vimeo.com/(\d{6,12})\b#', $code, $match):
            case preg_match('#//player.vimeo.com/video/(\d{6,12})\b#', $code, $match):
                $code = $match[1];
                break;
            default:
                $code = '';
        }

        return $code;
    }

    public function getEmbedUrl()
    {
        if (($code = $this->getCode())) {
            $code = "https://player.vimeo.com/video/{$code}";

            if ($this->getAutoplay()) {
                $code .= '&autoplay=1';
            }
        
            if ($this->getLoop()) {
                $code .= '&loop=1';
            }
        }

        return (string) $code;
    }

    public function getAspectPercentage()
    {
        switch ($this->getAspectRatio()) {
            case 'widescreen':
                $ratio = 16/9;
                break;
            default:
                $ratio = 4/3;
        }
        return sprintf('%.4g%%', 100 / $ratio);
    }

    protected function _toHtml()
    {
        return $this->getCode() ? parent::_toHtml() : '';
    }
}