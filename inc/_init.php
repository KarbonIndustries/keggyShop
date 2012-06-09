<?php
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'config.php');

#AUTOLOAD FUNCTIONS
function requiredFileExists($file)
{
	if(is_file($file))
	{
		require_once($file);
		return true;
	}
	return false;
}

function registerKeggy($class)
{
	$file = K_DIR . $class . '.php';
	if(requiredFileExists($file))
	{
		return;
	}
}

function registerFlourish($class)
{
	$file = FLOURISH_DIR . $class . '.php';
	if(requiredFileExists($file))
	{
		return;
	}
}

spl_autoload_register('registerKeggy');
spl_autoload_register('registerFlourish');
?>