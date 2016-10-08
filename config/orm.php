<?php
return [

	'toOne' => CoreWine\DataBase\ORM\Field\Relations\ToOne\Schema::class,
	'toMany' => CoreWine\DataBase\ORM\Field\Relations\ToMany\Schema::class,
	'string' => CoreWine\DataBase\ORM\Field\String\Schema::class,
	'id' => CoreWine\DataBase\ORM\Field\Identifier\Schema::class,
	'timestamp' => CoreWine\DataBase\ORM\Field\Timestamp\Schema::class,
	'password' => Auth\Field\Password\Schema::class,
	'text' => CoreWine\DataBase\ORM\Field\Text\Schema::class,
	'email' => CoreWine\DataBase\ORM\Field\Email\Schema::class,
	'datetime' => CoreWine\DataBase\ORM\Field\Datetime\Schema::class,
	'file' => CoreWine\DataBase\ORM\Field\File\Schema::class,
	'integer' => CoreWine\DataBase\ORM\Field\Integer\Schema::class,

];
?>