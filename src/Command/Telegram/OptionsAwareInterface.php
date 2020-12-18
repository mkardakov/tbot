<?php


namespace App\Command\Telegram;


interface OptionsAwareInterface
{

    public function getOptions() : array;
}