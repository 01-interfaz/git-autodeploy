<?php

function dd($value)
{
    echo "\n -------------- \n\n";
    var_dump($value);
    echo "\n -------------- \n\n";
    die();
}

function request_input(string $key): ?string
{
    if (isset($_REQUEST[$key])) return $_REQUEST[$key];
    return null;
}

function request_input_error(string $key): string
{
    $v = request_input($key);
    if ($v == null) throw  new Exception("No se encuentra el input ($key) en la solicitud");
    return $v;
}