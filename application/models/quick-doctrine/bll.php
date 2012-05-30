<?php
	/**
	* 
	*/
	class Bll extends Bll_lib
	{
	  protected static $_dal_name = NULL;

    public static function get($option, $required = NULL)
		{
			$result = call_user_func(static::$_dal_name. "::select", $option);
			static::required($result, $option, $required);
			return $result;
		}
		
		public static function count($option, $required = NULL)
		{
			$result = call_user_func(static::$_dal_name. "::count", $option);
			return $result;
		}

		public static function add($option)
		{
			return call_user_func(static::$_dal_name. "::insert", $option);
		}

		public static function edit($option)
		{
			return call_user_func(static::$_dal_name. "::update", $option);
		}

		public static function remove($option)
		{
			return call_user_func(static::$_dal_name. "::delete", $option);
		}

	}



