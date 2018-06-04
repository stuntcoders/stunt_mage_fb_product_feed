<?php

class Stuntcoders_FbProductFeed_Adminhtml_FbProductFeedController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        if ($feedId = $this->getRequest()->getParam('id')) {
            $feed = Mage::getModel('stuntcoders_fbproductfeed/feed')->load($feedId);
            Mage::register('stuntcoders_fbproduct_feed', $feed);
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $feed = Mage::getModel('stuntcoders_fbproductfeed/feed');
        $feed->addData(array(
            'id' => $this->getRequest()->getParam('id'),
            'path' => $this->getRequest()->getParam('path'),
            'title' => $this->getRequest()->getParam('title'),
            'description' => $this->getRequest()->getParam('description'),
            'brand' => $this->getRequest()->getParam('brand'),
            'categories' => implode(',', $this->getRequest()->getParam('categories')),
            'attributes' => $this->getRequest()->getParam('attributes'),
        ));

        $errors = $feed->validate();

        if (!empty($errors)) {
            foreach ($errors as $error) {
                Mage::getSingleton('core/session')->addError($error);
            }
        } else {
            $feed->save();
        }
        $this->generatecsvAction();
        return $this->_redirect('*/*/new', array('id' => $feed->getId()));
    }

    /**
     * @return Mage_Core_Controller_Varien_Action|Stuntcoders_FbProductFeed_Adminhtml_FbProductFeedController
     * @throws Exception
     */
    public function deleteAction()
    {
        $feedId = $this->getRequest()->getParam('id');
        if ($feedId) {
            $file = new Varien_Io_File();
            $feed = Mage::getModel('stuntcoders_fbproductfeed/feed')->load($feedId);
            $file->rm($feed->getFullPath());
            Mage::getModel('stuntcoders_fbproductfeed/feed')->setId($feedId)->delete();
            Mage::getSingleton('core/session')->addSuccess($this->__('Feed successfully deleted'));
        }

        return $this->_redirect('*/*/index');
    }

    /**
     * @return Mage_Adminhtml_Controller_Action
     */
    public function generatecsvAction()
    {
        $feedId = $this->getRequest()->getParam('id');
        try {
            $feed = Mage::getModel('stuntcoders_fbproductfeed/feed')->load($feedId);
            $feed->generateCsv();
            Mage::getSingleton('core/session')->addSuccess($this->__('Facebook feed successfully generated'));
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

        return $this->_redirectReferer('*/*/index');
    }
}
