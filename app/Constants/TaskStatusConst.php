<?php

namespace App\Constants;

class TaskStatusConst
{
    const TODO = 1;

    const IN_PROGRESS = 2;

    const DONE = 3;

    public static function getList()
    {
        return [
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'In Progress',
            self::DONE => 'Done',
        ];
    }
}
