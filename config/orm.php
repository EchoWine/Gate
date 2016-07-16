<?php
return [

	'toOne' => CoreWine\ORM\Field\Model\Schema::class,
	'toMany' => CoreWine\ORM\Field\CollectionModel\Schema::class,
	'string' => CoreWine\ORM\Field\String\Schema::class,
	'id' => CoreWine\ORM\Field\ID\Schema::class,
	'timestamp' => CoreWine\ORM\Field\Timestamp\Schema::class,
	'password' => Auth\Field\Password\Schema::class,
	'text' => CoreWine\ORM\Field\Text\Schema::class,
	'email' => CoreWine\ORM\Field\Email\Schema::class,

];
?>