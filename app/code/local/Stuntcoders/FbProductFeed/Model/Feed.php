<?php

class Stuntcoders_FbProductFeed_Model_Feed extends Mage_Core_Model_Abstract
{
    const CONDITION = 'new';
    const IN_STOCK = 'in stock';
    const OUT_OF_STOCK = 'out of stock';

    protected function _construct()
    {
        $this->_init('stuntcoders_fbproductfeed/feed');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $path = $this->getPath();

        return Mage::getUrl('*', array(
            '_direct' => "fbfeed/{$path}",
            '_type' => Mage_Core_Model_Store::URL_TYPE_MEDIA
        ));
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return Mage::getBaseDir('media') . DS . 'fbfeed';
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return $this->getBaseDir() . DS . $this->getPath();
    }

    /**
     * @return array
     */
    public function validate()
    {
        $errors = array();
        if (!$this->getPath()) {
            $errors[] = Mage::helper('stuntcoders_fbproductfeed')->__('Path is mandatory');
        }

        return $errors;
    }

    /**
     * @throws Mage_Core_Model_Store_Exception
     */
    public function generateCsv()
    {
        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation(1);

        $csv = new Varien_File_Csv();
        $io = new Varien_Io_File();
        $io->mkdir(dirname($this->getFullPath()), 755, true);

        $productsRow = array(
            array('id', 'title', 'description', 'link', 'image_link', 'condition ', 'availability', 'price', 'brand')
        );

        $productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->joinField(
                'category_id', 'catalog/category_product', 'category_id',
                'product_id=entity_id', null, 'left'
            )
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('category_id', array('in' => explode(',', $this->getCategories())))
            ->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
            ->addAttributeToFilter(
                'visibility',
                array('in' => array(
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                    Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                )
            )->groupByAttribute('entity_id');

        foreach ($productCollection as $product) {

            $productsRow[] = array(
                $product->getSku(),
                $product->getName(),
                $product->getDescription(),
                $product->getProductUrl(true),
                $this->_getImageUrl($product),
                self::CONDITION,
                $this->_getAvailability($product),
                $this->_getProductPrice($product),
                $this->getBrand(),
            );
        }

        $csv->saveData($this->getFullPath(), $productsRow);

        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
    }

    /**
     * @param $product
     * @return string
     */
    private function _getAvailability($product)
    {
        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
        if ($stockItem->getData('is_in_stock') === '1') {
            return self::IN_STOCK;
        } else {
            return self::OUT_OF_STOCK;
        }
    }

    /**
     * @param $product
     * @return string
     */
    private function _getImageUrl($product)
    {
        return (string) Mage::helper('catalog/image')->init($product, 'image')->resize(600);
    }

    /**
     * @param $product
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    private function _getProductPrice($product)
    {
        if ($product->getTypeId() === Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
            return number_format((float) Mage::helper('stuntcoders_fbproductfeed')
                    ->getLowestPrice($product->getId()), 2) . ' ' . Mage::app()->getStore()
                    ->getCurrentCurrency()->getCode();
        } else if ($product->getTypeId() === Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
                return number_format((float) Mage::helper('stuntcoders_fbproductfeed')
                        ->getGroupedPrice($product), 2)
                    . ' ' . Mage::app()->getStore()->getCurrentCurrency()->getCode();
        } else {
            return number_format((float) $product->getPrice(), 2)
                . ' ' . Mage::app()->getStore()->getCurrentCurrency()->getCode();
        }
    }
}
