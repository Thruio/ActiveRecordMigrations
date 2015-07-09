<?php

namespace Thru\ActiveRecordMigrations;

use Symfony\Component\Yaml\Yaml;
use Thru\ActiveRecord\ActiveRecord;
use Thru\ActiveRecord\DumbModel;

class Importer{
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
			if(!file_exists($output_dir)){
				mkdir($output_dir, 0777, true);
			}
			$input_file = $object->getClass(true) . ".yaml";
			if(!file_exists($output_dir . "/" . $input_file)){
				echo "Skipping {$input_file}. Does not exist.\n";
				return false;
			}
			$yaml = file_get_contents($output_dir . "/" . $input_file);

			$object_count = 0;

			foreach(Yaml::parse($yaml) as $import_object){
				/* @var $o ActiveRecord */
				$o = new $class();
				$primary = $o->getPrimaryKeyIndex()[0];
				$no_primary = false;
				if(isset($import_object[$primary])){
					$o = $class::search()->where($primary, $import_object[$primary])->execOne();
					if(!$o instanceof ActiveRecord){
						$o = new $class();
					}
					$no_primary = true;
				}
				foreach((array) $import_object as $key => $value){
					if($no_primary == false){
						$o->$key = $value;
					}elseif($no_primary && $key != $primary){
						$o->$key = $value;
					}
				}
				$o->save();
				$object_count++;
			}
			echo "Loaded " . strlen($yaml) . " bytes from {$input_file}. Created {$object_count} objects.\n";

		}else{
			die("Not an ActiveRecord object\n\n");
		}
	}
}