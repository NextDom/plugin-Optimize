<?php

class ajax
{
    public static function init()
    {
        MockedActions::add('ajax_init');
    }

    public static function error($msg)
    {
        MockedActions::add('ajax_error', $msg);
    }

    public static function success()
    {
        MockedActions::add('ajax_success');
    }
}
