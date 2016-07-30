<?php

namespace Test\Controller;

use CoreWine\Http\Router;
use CoreWine\SourceManager\Controller as Controller;
use CoreWine\Http\Cookie as Cookie;

use Api\Response\Response as Response;


class HelpersController extends Controller {



    public function __routes(){

        $this -> route('cookie') -> url("/test/help");
        $this -> route('redirectme') -> url("/test/redirectme");

        // Browser says: too many redirects
        //$this -> route('go_to_address') -> url("/test/redirectme/{here}");
        //$this -> route('go') -> url('/test/go/{here}');


    }

    function cookie() {

        // show some content
        return ($this -> response() -> setBody('chained'));
    }

    function redirectme() {
        return $this -> redirect() -> to('http://google.com');
    }

    function go($url) {
        if (!isset($url) || ($url === null)) {
            throw new \InvalidArgumentException("Argument not injected in go()");
            
        } 
        // where to go
        return $this -> redirect() -> to($url);
    }

}