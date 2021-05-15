<?php

function dd($value)
{
    echo "\n -------------- \n\n";
    var_dump($value);
    echo "\n -------------- \n\n";
    die();
}