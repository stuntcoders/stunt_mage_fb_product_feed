<?php

class Stuntcoders_FbProductFeed_Block_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('fb_product_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('stuntcoders_fbproductfeed/feed')->getCollection());
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('stuntcoders_fbproductfeed')->__('ID'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('stuntcoders_fbproductfeed')->__('Title'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'title',
        ));

        $this->addColumn('description', array(
            'header' => Mage::helper('stuntcoders_fbproductfeed')->__('Description'),
            'align' => 'left',
            'index' => 'description',
        ));

        $this->addColumn('brand', array(
            'header' => Mage::helper('stuntcoders_fbproductfeed')->__('Brand'),
            'align' => 'left',
            'index' => 'brand',
        ));

        $this->addColumn('link', array(
            'header' => Mage::helper('stuntcoders_fbproductfeed')->__('Link'),
            'align' => 'left',
            'index' => 'path',
            'renderer' => 'stuntcoders_fbproductfeed/index_grid_renderer_link',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/new', array('id' => $row->getId()));
    }
}
