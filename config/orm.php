<?php
return [

	'toOne' => CoreWine\ORM\Field\Relations\ToOne\Schema::class,
	'toMany' => CoreWine\ORM\Field\Relations\ToMany\Schema::class,
	'string' => CoreWine\ORM\Field\String\Schema::class,
	'id' => CoreWine\ORM\Field\Identifier\Schema::class,
	'timestamp' => CoreWine\ORM\Field\Timestamp\Schema::class,
	'password' => Auth\Field\Password\Schema::class,
	'text' => CoreWine\ORM\Field\Text\Schema::class,
	'email' => CoreWine\ORM\Field\Email\Schema::class,

];
?>