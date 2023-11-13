<?php

namespace Mattoid\MoneyHistory\model;

use Flarum\Database\AbstractModel;
use Flarum\Formatter\Formatter;

class UserOperateLog extends AbstractModel
{
    protected $table = "user_operate_log";

    /**
     * The text formatter instance.
     *
     * @var \Flarum\Formatter\Formatter
     */
    protected static $formatter;

    /**
     * Get the text formatter instance.
     *
     * @return \Flarum\Formatter\Formatter
     */
    public static function getFormatter()
    {
        return static::$formatter;
    }

    /**
     * Set the text formatter instance.
     *
     * @param \Flarum\Formatter\Formatter $formatter
     */
    public static function setFormatter(Formatter $formatter)
    {
        static::$formatter = $formatter;
    }
}
