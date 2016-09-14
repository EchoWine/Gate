<?php

namespace Test\Controller;

use CoreWine\Http\Router;
use CoreWine\Http\Controller as Controller;
use CoreWine\Http\Cookie as Cookie;
use CoreWine\Component\Flash;

use Api\Response\Response as Response;
use CoreWine\Http\Response\Response as BaseResponse;


class HelpersController extends Controller {

public $error = [
            'code' => 2, 
            'name' => 'fatal', 
            'details' => [
                'start' => '1.2.2016',
                'end' => '1.7.2016',
                'status' => 'running',
                ]
            ];

    public function __routes(){

        $this -> route('cookie') -> url("/test/help");
        $this -> route('redirectme') -> url("/test/redirectme");
        $this -> route('back') -> url("/test/back");
        $this -> route('flash') -> url("/test/flash");
        $this -> route('with') -> url("/test/with");
        $this -> route('show') -> url("/test/show");
        $this -> route('errors') -> url("/test/errors");

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

    // go to the precedent location
    function back() {
        return $this -> redirect() -> back();
    }

    // test flash msgs
    function flash() {
        Flash::add('error', 'This is an error.');
        Flash::add('error', 'A second error.');
        Flash::add('error', 'A final error.');

        $messages = Flash::get('error');
        $response = new Response;

        return $response -> setData($messages);
      
    }

    function with() {
        

        //$response = $this -> response() -> with(['error' => $error]);
        //$response = $this -> response() -> with('error',$error);

        $response = new BaseResponse;
        $response -> with('error',$this -> error);

        //var_dump(Flash::get('error'));
        return $response -> setBody(json_encode(Flash::get('error'), JSON_PRETTY_PRINT));
    }

    function show() {
        $response = new BaseResponse;
        //$response -> with('error',$this -> error); // this causes to print
        // the result twice!
        //return $response -> setBody(json_encode(Flash::get('error'), JSON_PRETTY_PRINT);

        return $this -> view('Test/oi') -> with('error', $this -> error);
    }

    function errors() {
        $response = new BaseResponse;

        return $this -> view('Test/errors') 
            //-> with('error', $this -> error)
            -> with(['error' => ['one', 'two', 'three']])
        -> with(['oi' => $this -> error]);
    }

}