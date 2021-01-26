<?php

namespace WeeklyCheckKeys\Utils;

class CommandCheck
{
    const COMMAND = ['weeklycheckroster', 'weeklycheck'];
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..//', '.env.local');
        $dotenv->load();
    }

    public static function verify(string $message): bool
    {
        if(substr($message, 0, 1) === $_SERVER['PREFIX_COMMAND_BOT']){
            return self::checkAvailableCommand($message);
        }
        return false;
    }

    public static function checkAvailableCommand(string $message): bool
    {
        if(in_array(strtok(substr($message, 1), " "), static::COMMAND)){
            return true;
        }
        return false;
    }
}
