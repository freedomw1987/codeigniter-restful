<?php
	/**
	* 
	*/
	require_once dirname(__FILE__).'/dal/dal_lib.php';
	
	class Dal extends Dal_lib
	{
    
    protected static $_model_name = NULL;
    protected static $_primary_key = array();
    
		public static function select($option = array())
		{
			try {
				$q = static::where($option);
				$result = $q->execute();
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
			}
			return $result;
		}

		public static function count($option = array())
		{
		  try {
		    $q = static::where($option);
		    $result = $q->count();
		  } catch (Exception $e) {
		    throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
		  }
		  return $result;
		}

		public static function update($option = array())
		{
		  extract($option);
		  try {
		    $q = Doctrine_Query::create()->update(static::$_model_name);
  			$table = Doctrine::getTable(static::$_model_name);
  			foreach ($table->getColumns() as $column => $column_properties){
  			  if ($column_properties['type'] == 'timestamp' || !isset($option[$column])) continue;
  			  $q->set($column, "'". $option[$column]."'");
  			}
  			$q->where('id = ?', $id);
  			$q->execute();
		  } catch (Exception $e) {
		    throw new Exception("[".__METHOD__."]".$e->getMessage(), 1);
		  }
      $result = static::select(array('id' => $id));
			return $result[0];
			
		}

		public static function insert($option = array())
		{
			$q = static::_newObject(static::$_model_name);
			static::_setValue($q, $option);
			$q->save();
			$q = static::find(static::$_model_name, array('id' => $q->id));
			return static::_toArray($q);
		}

		public static function delete($option = array())
		{
			extract($option);
			try {
			  $q = Doctrine_Query::create()
  			->delete(static::$_model_name)
  			->where('id = ?', $id)
  			->execute();
			} catch (Exception $e) {
			  throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
			}
		}

    public static function find($table, $option = array())
		{
			extract($option);
			try {
				$q = Doctrine::getTable($table)->find($id);
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 1);	
			}
			return $q;
		}
    
	}