<?php
$xpdo_meta_map['calculatorOder']= array (
  'package' => 'calculator',
  'version' => '1.1',
  'table' => 'calculator_order',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'status' => 0,
    'image' => NULL,
    'name' => NULL,
    'phone' => '',
    'email' => NULL,
    'address' => NULL,
    'text' => NULL,
    'cost' => 0.0,
    'chain' => NULL,
    'items' => NULL,
    'timestamp' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'status' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '2',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'image' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'address' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'text' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '512',
      'phptype' => 'string',
      'null' => true,
    ),
    'cost' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0.0,
    ),
    'chain' => 
    array (
      'dbtype' => 'JSON',
      'phptype' => 'json',
      'null' => true,
    ),
    'items' => 
    array (
      'dbtype' => 'JSON',
      'phptype' => 'json',
      'null' => true,
    ),
    'timestamp' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
  ),
  'indexes' => 
  array (
    'status' => 
    array (
      'alias' => 'status',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'status' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
