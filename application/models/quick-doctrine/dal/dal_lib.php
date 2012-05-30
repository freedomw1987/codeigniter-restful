<?php
  /**
  * 
  */
  class Dal_lib
  {
    static function where($option = array())
		{
			extract($option);
			$q = static::from(static::$_model_name, 'r');
			static::pagination($q, $offset, $limit);
			return $q;
		}

		static function from($table, $alias = NULL)
		{
			try {
				$query = Doctrine_Query::create()
				  ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
				if ($alias) {
					$query->from("$table as $alias");
				}else{
					$query->from("$table");
				}

			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
			}
			return $query;
		}

		static function leftJoin(&$query, $table)
		{
		  try {
				$query->leftJoin($table);
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
			}
		}

		static function innerJoin(&$query, $table)
		{
		  try {
				$query->innerJoin($table);
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
			}
		}


		static function andWhere(&$query, $attribute, $value = NULL, $sign = '=')
		{
			try {
				if ($value) {
					$query->andWhere("$attribute $sign ?", $value);
				}
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 1);
			}
		}

		static function andWhereIn(&$query, $attribute, $value = NULL)
		{
			try {
				if ($value) {
				  if (!is_array($value)) $value = array($value);
					$query->andWhereIn("$attribute", $value);
				}
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 1);
			}
		}

		static function andWhereNotIn(&$query, $attribute, $value = NULL)
		{
			try {
				if ($value) {
				  if (!is_array($value)) $value = array($value);
					$query->andWhereNotIn("$attribute", $value);
				}
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 1);
			}
		}
		
		static function search(&$query, $attribute = array(), $value = NULL)
		{
		  try {
		    if (!is_array($attribute)) {
		      $attribute = array($attribute);
		    }
		    $q = array();
		    foreach ($attribute as $attr) {
          $q[] = "$attr like '%$value%'";
		    }
		    $addQuery = implode(' OR ', $q);
		    $query->andWhere($addQuery);
		  } catch (Exception $e) {
		    throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
		  }
		}
		
		
		static function andWhereLike(&$query, $attribute, $value = NULL)
		{
		  try {
		    if ($value) {
		      $query->andWhere("$attribute like '%$value%'");
		    }
		  } catch (Exception $e) {
		    throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
		  }
		}
    
    
		static function pagination(&$query, $offset = null, $limit = null)
		{
			try {
				if ($offset) {
					$query->offset($offset);
				}
				if ($limit) {
					$query->limit($limit);
				}
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 1);
			}
		}
    
    static function orderBy(&$query, $sort_by = array(), $sort_order = array())
    {
      try {
        if (!is_array($sort_by)) {
          $sort_by = array($sort_by);
        }
        if (!is_array($sort_order)) {
          $sort_order = array($sort_order);
        }
        $order_by = __::zip($sort_by, $sort_order);
        foreach ($order_by as $key => $order_array) {
          $order_by[$key] = implode(' ', $order_array);
        }
        $query->orderBy(implode(', ', $order_by));
      } catch (Exception $e) {
        throw new Exception("[".__METHOD__."]". $e->getMessage(), 1);
      }
    }
    
		static function _newObject($table)
		{
			try {
				return new $table;
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 1);
			}
		}

		
		static function _setValue(&$query, $option = array())
		{
		  $columns = $query->getTable()->getColumns();			
		  try {
				foreach ($option as $key => $value) {
				  if (!array_key_exists($key, $columns)) continue;
					$query->$key = $value;
				}
			} catch (Exception $e) {
				throw new Exception("[".__METHOD__."]" .$e->getMessage(), 0);	
			}
		}

		static function _toArray(&$object)
		{
			try {
				if (method_exists($object, 'toArray')) {
					return $object->toArray();
				}

			} catch (Exception $e) {
				throw new Exception("[". __METHOD__ ."]". $e->getMessage(), 1);

			}			
		}
  }
  
