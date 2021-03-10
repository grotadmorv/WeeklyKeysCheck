<?php

namespace WeeklyCheckKeys\Action\Command;

interface CommandInterface
{
    public function getAlias(): string;
    public function addContent(string $content): void;
}
