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
    if (isset(request()[$key])) return request()[$key];
    return null;
}

function request_input_has(string $key): bool
{
    $v = request_input($key);
    return $v != null;
}

function request_header(string $key): ?string
{
    $key = str_replace("-", "_", strtoupper("HTTP_$key"));
    foreach ($_SERVER as $name => $value) if (strcmp(strtoupper($name), $key) == 0) return $value;
    return null;
}

function request_headers(): array
{
    if (!isset($_SERVER["__HEADERS__"])) {
        $headers = [];
        foreach ($_SERVER as $name => $value) $headers[strtoupper($name)] = $value;
        $_SERVER['__HEADERS__'] = $headers;
    }
    return $_SERVER["__HEADERS__"];
}

function request_header_validate(string $key, string $value): bool
{
    return strcmp(strtoupper(request_header($key)), strtoupper($value)) == 0;
}

function request(): array
{
    if (!isset($_SERVER['__REQUEST__'])) {
        $json = file_get_contents("php://input");
        $_SERVER['__REQUEST__'] = json_decode($json ?? "[]", true);
    }
    return $_SERVER['__REQUEST__'];
}