<?php

$this->startSetup();

$this->getConnection()->dropTable($installer->getTable('stuntcoders_fbproductfeed/feed'));
$table = $this->getConnection()
    ->newTable($this->getTable('stuntcoders_fbproductfeed/feed'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Feed id')
    ->addColumn('path', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => true,
        'default'   => '',
    ), 'Feed path')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable'  => true,
        'default'   => '',
    ), 'Feed title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
        'default'   => '',
    ), 'Feed description')
    ->addColumn('brand', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
        'default'   => '',
    ), 'Feed brand');

$this->getConnection()->createTable($table);

$this->endSetup();
