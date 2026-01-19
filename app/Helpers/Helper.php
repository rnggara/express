<?php


$config == 1;

if ($config == 0){
    URL::route('install');
} else {
    URL::route('login');
}

function get_config(){
    echo $config;
}

?>
