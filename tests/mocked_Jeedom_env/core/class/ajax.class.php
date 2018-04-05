<?php

class ajax
{
	public static function init($item = null) {
		return null;
	}

	public static function error($msg) {
		MockedActions::add(array('action' => 'ajax_error', 'value' => $msg));
	}
}
