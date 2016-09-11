<?php

namespace CoreWine\Console;

class Console{

	public static $commands;

	public static function addCommand($class){
		self::$commands[] = $class;
	}

	public static function getCommand($name){
		foreach(self::$commands as $command){
			if($command::$signature == $name)
				return $command;
		}
	}

	public function __construct(){
		
	}

	public function exec($argv){
		array_shift($argv);
		$command = self::getCommand($argv[0]);
		array_shift($argv);
		if($command){

			$command = new $command($argv);
			$command -> handle();
		}else{
			echo "unknow command";
		}
	}

}

?>