<?php

namespace Thru\ActiveRecordMigrations;

use Commando\Command;
use Symfony\Component\Yaml\Yaml;

class Migrator
{
	static public function Main(){

		$migrator_command = new Command();
		$migrator_command->option('i')
			->aka('import')
			->describedAs("Import fixtures from .")
			->boolean();
		$migrator_command->option('e')
			->aka('export')
			->describedAs("Export fixtures from .")
			->boolean();
		$migrator_command->option('class')
			->describedAs("fill this in");
		$migrator_command->option('migration_path')->aka('output')
			->require()
			->default("fixtures");

		if($migrator_command['export']){
			$classes_to_compute = [];

			if($migrator_command['class']){
				$classes_to_compute[] = $migrator_command['class'];
			}else{
				$array = Yaml::parse(file_get_contents(ACTIVERECORDMIGRATIONS_CWD . "/fixtures.yaml"));
				foreach($array['models'] as $model){
					$classes_to_compute[] = $model;
				}
			}
			foreach($classes_to_compute as $class){
				Exporter::Run($class, $migrator_command['migration_path']);
			}

		}else{
			$migrator_command->printHelp();
		}

	}
}