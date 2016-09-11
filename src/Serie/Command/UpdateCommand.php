<?php

namespace Serie\Command;
 
use CoreWine\Console\Command;
use CoreWine\Service\Serie;

class UpdateCommand extends Command{

	public static $signature = 'serie:update';

	public function handle(){

		Serie::update();

	}
}

?>