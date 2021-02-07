<?php


namespace App\Classes\Command;


interface ICommand
{
    public function prepare($payload) : bool;
    public function execute() : bool;
}