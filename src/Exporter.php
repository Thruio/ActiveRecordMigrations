<?php

namespace Thru\ActiveRecordMigrations;

use Symfony\Component\Yaml\Yaml;
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
				$schema = array_keys($row->getClassSchema());
				$data_array[] = $row->__ToArray($schema);
			}
			//var_dump($data);exit;
			if(!file_exists($output_dir)){
				mkdir($output_dir, 0777, true);
			}
			$output_file = $object->getClass(true) . ".yaml";
			$yaml = Yaml::dump($data_array);
			$output_file_path = $output_dir . "/" . $output_file;
			file_put_contents($output_file_path, $yaml);
			echo "Written " . strlen($yaml) . " bytes to {$output_file_path}\n";

		}else{
			die("Not an ActiveRecord object\n\n");
		}
	}
}