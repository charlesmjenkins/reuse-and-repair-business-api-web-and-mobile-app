<?php
// CS419 - Reuse & Repair Web App
// ---------------------------------------
// Charles Jenkins
//
// Title: php_crud_api_transform.php
//
// Description: Supporting functions for
// web service API library's integration
// with web app.
// ---------------------------------------
// Acknowledgement: This code was provided along
// with the web service library we used. Thanks to 
// https://github.com/mevdschee/php-crud-api

function php_crud_api_transform(&$tables) {
	$get_objects = function (&$tables,$table_name,$where_index=false,$match_value=false) use (&$get_objects) {
		$objects = array();
		foreach ($tables[$table_name]['records'] as $record) {
			if ($where_index===false || $record[$where_index]==$match_value) {
				$object = array();
				foreach ($tables[$table_name]['columns'] as $index=>$column) {
					$object[$column] = $record[$index];
					foreach ($tables as $relation=>$reltable) {
						foreach ($reltable['relations'] as $key=>$target) {
							if ($target == "$table_name.$column") {
								$column_indices = array_flip($reltable['columns']);
								$object[$relation] = $get_objects($tables,$relation,$column_indices[$key],$record[$index]);
							}
						}
					}
				}
				$objects[] = $object;
			}
		}
		return $objects;
	};
	$tree = array();
	foreach ($tables as $name=>$table) {
		if (!isset($table['relations'])) {
			$tree[$name] = $get_objects($tables,$name);
			if (isset($table['results'])) {
				$tree['_results'] = $table['results'];
			}
		}
	}
	return $tree;
}
?>