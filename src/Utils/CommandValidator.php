<?php

namespace WeeklyCheckKeys\Utils;

class CommandValidator
{
    const COMMAND_WEEKLY_CHECK = 'weeklycheck';
    const COMMAND_WEEKLY_CHECK_ROSTER = 'weeklycheckroster';
    const COMMAND_HELP = 'help';
    const COMMAND = [
        self::COMMAND_WEEKLY_CHECK, 
        self::COMMAND_WEEKLY_CHECK_ROSTER,
        self::COMMAND_HELP
    ];

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

    public static function getCommandBase(string $message): string
    {
        return strtok(substr($message, 1), " ");
    }
}
