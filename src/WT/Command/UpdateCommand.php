<?php

namespace WT\Command;
 
use CoreWine\Console\Command;
use WT\Service\WT;

class UpdateCommand extends Command{

	public static $signature = 'wt:update';

	public function handle(){

		echo "Initialization...\n\n";
		$update = WT::update();

		foreach($update as $u){
			echo "Updating... ".$u['name']."\n";

			WT::sync(null,$u['type'],$u['id']);
			
		}

		echo "\nCompleted";
	}
}

?>