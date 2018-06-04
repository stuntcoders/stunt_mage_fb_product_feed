<?php

class Stuntcoders_FbProductFeed_Block_Index_Grid_Renderer_Link
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        /** @var Stuntcoders_FbProductFeed_Model_Feed $row */
        $caption = Mage::helper('stuntcoders_fbproductfeed')->__($row->getPath());

        $validator = new Zend_Validate_File_Exists();
        $validator->addDirectory($row->getBaseDir());
        if (!$validator->isValid($row->getPath())) {
            $caption = Mage::helper('stuntcoders_fbproductfeed')->__('Csv file is not generated');
        }

        $this->getColumn()->setActions(
            array(array(
                'url' => $row->getUrl(),
                'caption' => $caption,
            ))
        );

        return parent::render($row);
    }
}
