<?php

class SystemInfoView extends View{
	

	public static function template($path){

		if(true)
			Module::TemplateOverwrite('content','page');

		$path = $path."/templates";
		Module::TemplateAggregate('nav','nav',99);
	}

}

?>