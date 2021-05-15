<?php


namespace App\Classes\Command;


interface ICommand
{
    public function prepare(CommandPayload $payload) : bool;
    public function execute() : bool;
}