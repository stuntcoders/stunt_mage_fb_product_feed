<?php

class Stuntcoders_FbProductFeed_Block_AddForm extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_headerText = Mage::helper('stuntcoders_fbproductfeed')->__('Facebook Product Feed Manager');;
        parent::__construct();
        $this->setTemplate('stuntcoders/fbproductfeed/add.phtml');
    }

    protected function _prepareLayout()
    {
        $feed = Mage::registry('stuntcoders_fbproduct_feed');

        /** @var Mage_Adminhtml_Block_Widget_Button $saveButton */
        $saveButton = $this->getLayout()->createBlock('adminhtml/widget_button');
        $saveButton->setData(array(
            'label' =>  Mage::helper('stuntcoders_fbproductfeed')->__('Save'),
            'onclick' => "fbproductfeed_form.submit()",
            'class' => 'save'
        ));

        $this->setChild('fbproductfeed.savenew', $saveButton);

        if ($feed) {
            /** @var Mage_Adminhtml_Block_Widget_Button $backButton */
            $backButton = $this->getLayout()->createBlock('adminhtml/widget_button');
            $backButton->setData(array(
                    'label' =>  Mage::helper('stuntcoders_fbproductfeed')->__('Back'),
                    'onclick' => "setLocation('" . $this->getUrl('*/*/index') . "')",
                    'class' => 'back')
            );

            /** @var Mage_Adminhtml_Block_Widget_Button $deleteButton */
            $deleteButton = $this->getLayout()->createBlock('adminhtml/widget_button');
            $deleteButton->setData(array(
                    'label' =>  Mage::helper('stuntcoders_fbproductfeed')->__('Delete'),
                    'onclick' => $this->_getDeleteOnClickHandler($feed),
                    'class' => 'delete')
            );

            /** @var Mage_Adminhtml_Block_Widget_Button $generateButton */
            $generateButton = $this->getLayout()->createBlock('adminhtml/widget_button');
            $generateButton->setData(array(
                'label' =>  Mage::helper('stuntcoders_fbproductfeed')->__('Generate CSV File'),
                'onclick' => $this->_getGenerateCsvOnClickHandler($feed),
                'class' => 'generate'
            ));

            $this->setChild('fbproductfeed.back', $backButton);
            $this->setChild('fbproductfeed.delete', $deleteButton);
            $this->setChild('fbproductfeed.generate', $generateButton);
        }

        $this->setChild('fbproductfeed_form', $this->getLayout()->createBlock('stuntcoders_fbproductfeed/add_form'));
    }

    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    public function getFormHtml()
    {
        return $this->getChildHtml('fbproductfeed_form');
    }

    /**
     * @param $feed
     * @return string
     */
    protected function _getDeleteOnClickHandler($feed)
    {
        return "setLocation('" . $this->getUrl('*/*/delete', array('id' => $feed->getId())) . "')";
    }

    /**
     * @param $feed
     * @return string
     */
    protected function _getGenerateCsvOnClickHandler($feed)
    {
        return "setLocation('" . $this->getUrl('*/*/generatecsv', array('id' => $feed->getId())) . "')";
    }
}
