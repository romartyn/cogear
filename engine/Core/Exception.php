<?php


/**
 * Exception handler
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright	Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Core_Exception extends Exception
{
	/**
	 * Human readable errors types
	 * @var array
	 */
	public static $php_errors = array(
		E_ERROR => 'Fatal Error',
		E_USER_ERROR => 'User Error',
		E_PARSE => 'Parse Error',
		E_WARNING => 'Warning',
		E_USER_WARNING => 'User Warning',
		E_STRICT => 'Strict',
		E_NOTICE => 'Notice',
		E_RECOVERABLE_ERROR => 'Recoverable Error',
	);

	public static $shutdown_errors = array(E_PARSE, E_ERROR, E_USER_ERROR);

	public function __construct($message = null, $code = 0, $previous = null)
	{
		parent::__construct($message, $code);
	}

	/**
	 * Beautiful display of errors
	 * @static
	 * @param $trace
	 * @param $type
	 * @param $code
	 * @param $message
	 * @param $file
	 * @param $line
	 * @return string rendered HTML
	 */
	public static function display($trace, $type, $code, $message, $file, $line)
	{
		try {
			$tpl = new Template('Core.exception');

			$tpl->trace = $trace;
			$tpl->code = $code;
			// Get the exception information
			$tpl->type = $type;
			$tpl->message = $message;
			$tpl->file = $file;
			$tpl->line = $line;

			// Include the exception HTML
			echo $tpl->render();
		}
		catch (Exception $e)
		{
			// Display the exception text
			echo self::toString($e), "\n";

			// Exit with an error status
			exit(1);
		}
	}

	/**
	 * Handling incoming eceptions
	 * @static
	 * @param Exception $e
	 * @return void
	 */
	public static function handler(Exception $e)
	{
        if(function_exists('error') && function_exists('t') && (cogear()->l18n instanceof Gear)) {
            error(self::toString($e));
        } else {
            if(defined('DEVELOPMENT')) {
                $trace = $e->getTrace();
                $code = $e->getCode();
                $type = get_class($e);
                $message = $e->getMessage();
                $file = $e->getFile();
                $line = $e->getLine();
                //$error = self::toString($e);

                echo self::display($trace, $type, $code, $message, $file, $line);
            } else {
                echo self::toString($e);
            }
        }
	}

	/**
	 * Handling incoming errors
	 * @static
	 * @param $code
	 * @param $message
	 * @param null $file
	 * @param null $line
	 * @return bool
	 */
	public static function errorHandler($code, $message, $file = NULL, $line = NULL)
	{

		if (error_reporting() & $code) {
            // disable error capturing to avoid recursive errors
            restore_error_handler();
            restore_exception_handler();

            if(function_exists('error') && function_exists('t') && (cogear()->l18n instanceof Gear)) {
                error(t('Error in file <b>%s</b> was found at line <b>%d</b>: <blockquote>%s</blockquote>','Errors',$file,$line,$message),t('Error'));
            } else {

                $trace = debug_backtrace();

                if (count($trace) > 2)
                    $trace = array_slice($trace, 2);

                if (isset(Core_Exception::$php_errors[$code])) {
                    // Use the human-readable error name
                    $code = Core_Exception::$php_errors[$code];
                }

                if (version_compare(PHP_VERSION, '5.3', '<')) {
                    // Workaround for a bug in ErrorException::getTrace() that exists in
                    // all PHP 5.2 versions. @see http://bugs.php.net/bug.php?id=45895
                    for ($i = count($trace) - 1; $i > 0; --$i)
                    {
                        if (isset($trace[$i - 1]['args'])) {
                            // Re-position the args
                            $trace[$i]['args'] = $trace[$i - 1]['args'];

                            // Remove the args
                            unset($trace[$i - 1]['args']);
                        }
                    }
                }

                echo self::display($trace, 0, $code, $message, $file, $line);
            }
		}

		return TRUE;
	}

	public function __toString()
	{
		self::toString($this);
	}


	public static function toString(Exception $e)
	{
		return sprintf('%s [ %s ]: %s ~ %s [ %d ]',
		               get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
	}
}