<?php

class Stuntcoders_FbProductFeed_Block_Index extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _prepareLayout()
    {
        $addButton = $this->getLayout()->createBlock('adminhtml/widget_button');
        $addButton ->setData(array(
            'label' => Mage::helper('stuntcoders_fbproductfeed')->__('Add New Feed'),
            'onclick' => "setLocation('" . $this->getUrl('*/*/add') . "')",
            'class' => 'add'
        ));

        $this->setChild('fbproductfeed.addnew', $addButton);
    }
}
