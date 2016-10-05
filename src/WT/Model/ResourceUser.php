<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;
use Auth\Model\User;

class ResourceUser extends Model{

    /**
     * Table name
     *
     * @var
     */
    public static $table = 'resources_users';

    /**
     * Set schema fields
     *
     * @param Schema $schema
     */
    public static function fields($schema){

        $schema -> id();
        
        $schema -> toOne(User::class,'user') -> required();

        $schema -> toOne(Resource::class,'resource') -> required();

        
    }
}

?>