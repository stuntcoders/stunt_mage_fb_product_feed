<?php

 class Stuntcoders_FbProductFeed_Model_Observer
 {
     public function setFeeds()
     {
         $feedModel = Mage::getModel('stuntcoders_fbproductfeed/feed');
         $feeds = $feedModel->getCollection();

         foreach ($feeds as $feed) {
             $feedModel->load($feed->getId())->generateCsv();
         }
     }
 }
