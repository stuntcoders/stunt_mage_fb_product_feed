<?php

class Stuntcoders_FbProductFeed_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCategoriesOptions()
    {
        $categories = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('name', array('neq' => ''))
            ->setOrder('path', Varien_Data_Collection_Db::SORT_ORDER_ASC);

        $values = array();
        foreach ($categories as $category) {
            $values[] = array(
                'value' => $category->getId(),
                'label' => str_repeat('–', $category->getLevel()) . ' ' . $category->getName(),
            );
        }

        return $values;
    }

    /**
     * @return float
     */
    public function getLowestPrice($product_id)
    {
        /* @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($product_id);
        if ((int) $product->getPriceType() === Mage_Bundle_Model_Product_Price::PRICE_TYPE_FIXED) {
            $price = $product->getFinalPrice();
        } else {
            $selectionCollection = $product->getTypeInstance(true)->getSelectionsCollection(
                $product->getTypeInstance(true)->getOptionsIds($product), $product
            );

            $prices = array();
            $price = 0;

            foreach ($selectionCollection as $option) {
                $thisPrice = Mage::getModel('bundle/product_price')->getSelectionPrice($product, $option);
                $thisOptionId = $option->getData('option_id');

                if ($prices[$thisOptionId]) {
                    if ($thisPrice < $prices[$thisOptionId]) {
                        $prices[$thisOptionId] = $thisPrice;
                    }
                } else {
                    $prices[$thisOptionId] = $thisPrice;
                }
                $price += $prices[$thisOptionId];
            }
        }

        return Mage::app()->getStore()->roundPrice($price);
    }

    /**
     * @param $product
     * @return float
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getGroupedPrice($product)
    {
        $ogPrice = 0;
        $_associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);

        foreach ($_associatedProducts as $_associatedProduct) {
            $ogPrice += $_associatedProduct->getPrice();
        }
        return Mage::app()->getStore()->roundPrice($ogPrice);
    }
}
