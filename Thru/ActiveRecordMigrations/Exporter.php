<?php

namespace Thru\ActiveRecordMigrations;

use Thru\ActiveRecord\ActiveRecord;
use Thru\ActiveRecord\DumbModel;

class Exporter{
	static public function Run($class, $output_dir){
		$object = new $class();
		if($object instanceof ActiveRecord){
			$data = $object::search()->exec();
			$data_array = array();

			foreach($data as $row){
				/* @var $row ActiveRecord */
				$schema = array_keys($row->get_class_schema());
				$data_array[] = $row->__ToArray($schema);
			}
			if(!file_exists($output_dir)){
				mkdir($output_dir, 0777, true);
			}
			$output_file = $object->get_class(true) . ".json";
			$json = json_encode($data_array);
			file_put_contents($output_dir . "/" . $output_file, $json);
			echo "Written " . strlen($json) . " bytes to {$output_file}\n";

		}else{
			die("Not an ActiveRecord object\n\n");
		}
	}
}