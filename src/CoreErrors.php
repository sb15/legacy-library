<?php

class CoreErrors
{
	/**
	 * @var Raven_Client
	 */
	public static $client = null;

    public static function init($sentryDSN)
    {        
		$client = new Raven_Client($sentryDSN);

		// Install error handlers and shutdown function to catch fatal errors
		$error_handler = new Raven_ErrorHandler($client);
		$error_handler->registerExceptionHandler();
		$error_handler->registerErrorHandler();
		$error_handler->registerShutdownFunction();
		
		self::$client = $client;
    }

}

