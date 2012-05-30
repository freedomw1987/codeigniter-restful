<?php
  class Chatroom_model extends Doctrine_Record
  { 
    public function setTableDefinition()
    {
      $this->hasColumn('id', 'int', 4, array(
        'primary' => true,
        'notnull' => true,
        'autoincrement' => true
      ));
      $this->hasColumn('name', 'varchar', 255);
      $this->hasColumn('created_at', 'datetime');
      $this->hasColumn('updated_at', 'datetime');
    }
    
    public function setUp()
    {
      $this->setTableName('chatroom');
      $this->actAs('Timestampable');
    }
  }
  