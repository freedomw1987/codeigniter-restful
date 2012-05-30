<?php
  /**
  * 
  */
  class Chatroom_dal extends Dal
  {
    protected static $_model_name = 'Chatroom_model';
    protected static $_primary_key = array('id');
    
    public static function where($option)
    {
      extract($option);
      $q = self::from(self::$_model_name, 'c');
      self::andWhereIn($q, 'id', $id);
      self::pagination($q, $offset, $limit);
      return $q;
    }
  }
  
