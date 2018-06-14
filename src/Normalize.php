<?php

namespace OSvCPHP;

use OSvCPHP;

class Normalize
	{
		static function results_to_array($response_object)
		{

			if(isset($response_object->status) && !in_array($response_object->status, array(200,201))){
				return get_object_vars($response_object);
			}else{
				if(isset($response_object->items)){
					$results_array = $response_object->items;
				}else if(isset($response_object->columnNames)){
					$results_array = $response_object;
				}
			}

			return self::check_for_items_and_rows($results_array);
		}

		private static function iterate_through_rows($item)
		{
			$results_array = array();
			foreach ($item->rows as $rowIndex => $row) {
				$result_hash = array();
				foreach ($item->columnNames as $columnIndex => $column) {
					$result_hash[$column] = $row[$columnIndex];
				};
				array_push($results_array, $result_hash);
			}
			return $results_array;
		}

		private static function results_adjustment($final_arr)
		{
			if(sizeof($final_arr) == 1 && gettype($final_arr) == "array" ){
				return $final_arr[0];
			}else{
				return $final_arr;
			}
		}

		private static function check_for_items_and_rows($results_array)
		{
			if(isset($results_array->rows)){
				return self::iterate_through_rows($results_array);
			}else{
				$results = array();
				foreach ($results_array as $item) {
					array_push($results, self::iterate_through_rows($item));
				}
				return self::results_adjustment($results);
			}
		}
	}

	