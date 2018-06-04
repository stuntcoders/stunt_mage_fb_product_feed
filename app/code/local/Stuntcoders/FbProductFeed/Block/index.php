<?php

class Stuntcoders_FbProductFeed_Block_Index extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'stuntcoders_fbproductfeed';
        $this->_controller = 'index';
        $this->_headerText = $this->__('Facebook Product Feed');
        return parent::__construct();
    }
}
