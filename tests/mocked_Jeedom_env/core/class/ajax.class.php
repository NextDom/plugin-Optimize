<?php

class ajax
{
	public static function init() {
		MockedActions::add(array('action' => 'ajax_init'));
	}

	public static function error($msg) {
		MockedActions::add(array('action' => 'ajax_error', 'value' => $msg));
	}

	public static function success() {
		MockedActions::add(array('action' => 'ajax_success'));
	}
}
