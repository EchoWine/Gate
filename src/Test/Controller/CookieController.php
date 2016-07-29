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
        //$first_cookie = new Cookie('first', true, 12123, '/', 'gate-cms.com', false); // does not work

        $first_cookie = new Cookie('first', true, 0, '/', 'localhost'); // domain is right

        //$first_cookie  = new Cookie('first', true);
        //$first_cookie -> initialize(Cookie::getDefaults()); // use default values
        $cookie = new Cookie('second', true);

        //$third = new Cookie('third', 'last cookie', 0, '/', '127.0.0.1'); // does not work

        // attach it to the response
        $response = new Response;

        $response -> attach($first_cookie);
        $response -> attach($cookie);
        //$response -> attach($third);

        // add some headers
        $response -> header('MyHeader', 'someValue');

        // body
        $response -> setData('all good');

        return ($response);
         
    }

}