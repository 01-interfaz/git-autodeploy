<?php


namespace App\Classes\Repository;


interface IDriver
{
    //Leer los datos de la consulta
    public function readContent() : bool;

    //Verificar que servicio esta haciendo la llamada
    public function checkSender() : bool;

    //Verificar que el token
    public function checkToken() : bool;
}