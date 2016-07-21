<?php

namespace Test\Controller;

use CoreWine\Http\Router;
use CoreWine\SourceManager\Controller as Controller;
use CoreWine\Http\Cookie as Cookie;

use Api\Response\Response as Response;


class CookieController extends Controller {



    public function __routes(){

        $this -> route('cookie') -> url("/test/cookie");

    }

    function cookie() {
        //$name = null, $value = null, $expire = 0, $path = '/', $domain, $secure = false

        // create a new cookie
        $cookie = new Cookie('second', true, 0, '/', 'gate-cms.com', false);

        // attach it to the response
        $response = new Response;
        $response -> attach($cookie);

        // add some headers
        $response -> header('Content-Type', 'json/application');
 
        $response -> header('MyHeader', 'someValue');

        $response -> setData('All good');

        return ($response);

    }

}