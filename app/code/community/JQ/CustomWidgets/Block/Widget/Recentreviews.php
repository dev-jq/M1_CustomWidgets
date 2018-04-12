<?php

class JQ_CustomWidgets_Block_Widget_Recentreviews extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {

    public function getRecentSize(){ 
        return $this->getData('recent_size');
    }

    public function getDisplayMode(){ 
        return $this->getData('display_mode');
    }

    public function getImageWidth(){ 
        return $this->getData('image_width');
    }

    public function getImageHeight(){ 
        return $this->getData('image_height');
    }

    protected function getProductCollection()
    {
        $mode = $this->getDisplayMode();
        if($mode == 0) {
            if (Mage::registry('current_category')) {
                return $this->getLastReviewedProductsInCategory();
            }
        } else {
            return $this->getLastReviewedProductsInAll();
        }
        return null;
    }

    public function getLastReviewedProductsInCategory() {
        $count = $this->getRecentSize();
        $productsIds = array();
        $current_category_id = Mage::registry('current_category')->getId();
        $current_category = Mage::getModel('catalog/category')->load($current_category_id);
        $allReviews = Mage::getModel('review/review')
            ->getResourceCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->setDateOrder('DESC')
            ->addRateVotes();
        foreach($allReviews as $review) {
            $productsIds[] = $review->getEntityPkValue();
        }
        $uniqueProductsIds = array_unique($productsIds);
        $finallyProductsIds = array_slice($uniqueProductsIds, 0, $count);
        $products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', array('in' => $finallyProductsIds))
            ->addCategoryFilter($current_category)
            ->addUrlRewrite();

        $products->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . implode(',', $finallyProductsIds).')'));
        return $products;
    }

    public function getLastReviewedProductsInAll() {
        $count = $this->getRecentSize();
        $productsIds = array();
        $allReviews = Mage::getModel('review/review')
            ->getResourceCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->setDateOrder('DESC')
            ->addRateVotes();
        foreach($allReviews as $review) {
            $productsIds[] = $review->getEntityPkValue();
        }
        $uniqueProductsIds = array_unique($productsIds);
        $finallyProductsIds = array_slice($uniqueProductsIds, 0, $count);
        $products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', array('in' => $finallyProductsIds))
            ->addUrlRewrite();

        $products->getSelect()->order(new Zend_Db_Expr('FIELD(e.entity_id, ' . implode(',', $finallyProductsIds).')'));
        return $products;
    }
}