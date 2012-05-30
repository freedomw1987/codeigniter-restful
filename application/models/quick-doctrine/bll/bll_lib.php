<?php
  /**
  * 
  */
  class Bll_lib
  {
		static function required(&$result, $option, $required = NULL)
		{
			$bool = true;

			if ($required != NULL) {
				foreach ($required as $key) {
					if ($option[$key] == NULL) {
						$bool = false;
						break;
					}
				}
			}				
			$result = $bool? $result: NULL;	
		}
  }
  
