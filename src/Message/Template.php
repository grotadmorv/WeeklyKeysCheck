<?php

namespace WeeklyCheckKeys\Message;

use WeeklyCheckKeys\Exception\ErrorCode;

class Template
{
    public static function getMessageConfig(): array
    {
        return json_decode(file_get_contents(__DIR__.'/../../data/message/message.json'), true);
    }

    public static function getNoKeysMessage(): string
    {
        return self::getMessageConfig()['player_no_keys'];
    }


    public static function getMessageWeekly(): string
    {
        return self::getMessageConfig()['message_weekly'];
    }

    public static function getErrorArgsMessage(): string 
    {
        return self::getMessageConfig()[ErrorCode::ERROR_WEEKLY_CHECK_ARGS_NOT_ACCEPTABLE];
    }
}
