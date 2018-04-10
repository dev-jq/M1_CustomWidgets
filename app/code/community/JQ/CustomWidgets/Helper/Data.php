<?php

/**
  * Sample Widget Helper
  */
class JQ_CustomWidgets_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getLastProductReview($productId){
		$_reviews = Mage::getModel('review/review')->getResourceCollection();
        $_reviews->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addEntityFilter('product', $productId)
                    ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                    ->setDateOrder()
                    ->addRateVotes();
        $lastReview = $_reviews->getFirstItem();
        return $lastReview;
	}
}