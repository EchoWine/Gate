<?php
return [

	'toOne' => CoreWine\ORM\Field\Schema\ModelField::class,
	'toMany' => CoreWine\ORM\Field\Schema\CollectionModelField::class,
	'string' => CoreWine\ORM\Field\Schema\StringField::class,
	'id' => CoreWine\ORM\Field\Schema\IDField::class,
	'timestamp' => CoreWine\ORM\Field\Schema\TimestampField::class,
	'password' => Auth\Field\Password\Schema::class,
	'email' => CoreWine\ORM\Field\Schema\EmailField::class,

];
?>