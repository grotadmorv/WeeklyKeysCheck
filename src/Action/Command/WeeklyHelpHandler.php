<?php

namespace WeeklyCheckKeys\Action\Command;

use React\Promise\Deferred;
use WeeklyCheckKeys\Message\Template;
use WeeklyCheckKeys\Action\Command\WeeklyHelp;

class WeeklyHelpHandler
{
    public function __invoke(WeeklyHelp $command, Deferred $deferred)
    {
        return $deferred->resolve(Template::getHelpMessage());
    }
}
