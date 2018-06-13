<?php

$this->startSetup();

$table = $this->getConnection()
    ->addColumn($this->getTable('stuntcoders_fbproductfeed/feed'), 'categories', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'default' => '',
        'comment' => 'Categories',
    ));

$this->endSetup();