<?php

include "../main.php";

use CoreWine\Http\Request as Request;

Request::setCookie('test',time(true));
Request::setSession('test',time(true));


echo Request::getCookie('test');
echo "<br>";
echo Request::getSession('test');
?>