<?php

class Stuntcoders_FbProductFeed_Block_Add_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * {@inheritdoc}
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'fbproductfeed_form',
            'name' => 'fbproductfeed_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $data = array();
        
        if (Mage::registry('stuntcoders_fbproduct_feed')) {
            $data = Mage::registry('stuntcoders_fbproduct_feed')->getData();
        }

        $fieldset = $form->addFieldset('fbproductfeed_form', array(
            'legend' => Mage::helper('stuntcoders_fbproductfeed')->__('Facebook Product Feed')
        ));

        $fieldset->addField('path', 'text', array(
            'label' => Mage::helper('stuntcoders_fbproductfeed')->__('Feed Name.csv'),
            'name' => 'path',
            'required' => true,
        ));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('stuntcoders_fbproductfeed')->__('Feed Title'),
            'name' => 'title',
            'required' => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'label' => Mage::helper('stuntcoders_fbproductfeed')->__('Feed Description'),
            'name' => 'description',
            'required'  => true,
        ));

        $fieldset->addField('brand', 'text', array(
            'label' => Mage::helper('stuntcoders_fbproductfeed')->__('Brand'),
            'name' => 'brand',
            'required' => true,
        ));

        $fieldset->addField('categories', 'multiselect', array(
            'label' => Mage::helper('stuntcoders_fbproductfeed')->__('Feed Categories'),
            'name' => 'categories',
            'values' => Mage::helper('stuntcoders_fbproductfeed')->getCategoriesOptions(),
            'required' => true,
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        $form->setValues($data);

        return parent::_prepareForm();
    }
}
