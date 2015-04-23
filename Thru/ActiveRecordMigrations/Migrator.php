<?php

namespace Thru\ActiveRecordMigrations;

use Commando\Command;

class Migrator
{
	static public function Main(){

		$migrator_command = new Command();
		$migrator_command->option('i')
			->aka('import')
			->describedAs("Import migrations from .")
			->boolean();
		$migrator_command->option('e')
			->aka('export')
			->describedAs("Export migrations from .")
			->boolean();
		$migrator_command->option('class')
			->require();
		$migrator_command->option('migration_path')->aka('output')
			->require()
			->default("migrations");

		if($migrator_command['export']){
			$classes_to_compute = [];

			if($migrator_command['class']){
				$classes_to_compute = $migrator_command['class'];
			}else{
				yaml
			}
			foreach($classes_to_compute as $class){
				Exporter::Run($class, $migrator_command['migration_path']);
			}

		}else{
			$migrator_command->printHelp();
		}

	}
}