<?php

/**
 *  Benchmark Gear
 *
 *
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2010, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Benchmark
 * @subpackage
 * @version		$Id$
 */
class Dev_Gear extends Gear {

    protected $name = 'Developer';
    protected $description = 'Calculate cogear performance at current system configuration.';
    protected $order = 0;
    /**
     * Benchmark points
     *
     * @param
     */
    protected $points = array();

    /**
     * Initialization
     */
    public function init() {
        parent::init();
        $this->addPoint('system.begin');
        hook('done', array($this, 'finalPoint'));
    }

    /**
     * Add benchmark info to user panel
     * 
     * @param   string  $name 
     * @param object $cp 
     */
    public function menu($name, &$cp) {
//        if($this->user->id != 1) return;
//        switch ($name) {
//            case 'user':
//                $cp->{Url::gear('dev')} =  t('Developer');
//                $cp->{Url::gear('dev')}->order = 98;
//                break;
//        }
    }

    /**
     * Add final point and show calculations for system benchmark
     */
    public function finalPoint() {
        $this->addPoint('system.end');
        if (access('development')) {
            $cogear = getInstance();
            $template = new Template('Dev.results');
            $template->data = Dev_Gear::humanize($cogear->dev->measurePoint('system'));
            append('footer', $template->render());
            js($this->folder . '/js/inline/debug.js');
        }
    }

    /**
     * Add point
     *
     * @param	string	$name
     */
    public function addPoint($name) {
        if (!isset($this->points[$name])) {
            $this->points[$name] = array(
                'time' => microtime() - IGNITE,
                'memory' => memory_get_usage(),
            );
        }
    }

    /**
     * Get points
     */
    public function getPoints($name = '') {
        if (!$name) {
            $this->addPoint('system.end');
            return $this->points;
        }
        else
            return isset($this->points[$name]) ? $this->points[$name] : NULL;
    }

    /**
     * Measure points
     * There should be two point. One with '.being' suffix, other with '.end'
     *
     * @param	string	$point
     */
    public function measurePoint($point) {
        $result = array();
        if (isset($this->points[$point . '.begin']) && isset($this->points[$point . '.end'])) {
            $result = array(
                'time' => $this->points[$point . '.end']['time'] - $this->points[$point . '.begin']['time'],
                'memory' => $this->points[$point . '.end']['memory'] - $this->points[$point . '.begin']['memory'],
            );
        }
        return $result;
    }

    /**
     * Transform point to human readable form
     *
     * @param	array	$point
     * @return	array
     */
    public static function humanize($point, $measure = null) {
        if (is_array($point) && !isset($point['time'])) {
            $result = array();
            foreach ($point as $key => $dot) {
                $result[$key] = self::humanize($dot, $measure);
            }
            return $result;
        }
        return array(
            'time' => self::microToSec($point['time']),
            'memory' => Filesystem::fromBytes($point['memory'], $measure),
        );
    }

    /**
     * Convert microtime to seconds
     *
     * @param	int	$microtime
     * @return	float
     */
    public static function microToSec($microtime) {
        return $microtime;
    }

	/**
     * Protect full paths, replacing them to constants
	 *     // Displays ENGINE/Core/Cogear.php
	 *     echo Dev::path('engine/Core/Cogear.php');
	 *
	 * @param   string  path to debug
	 * @return  string
	 */
	public static function path($file)
	{
		if (strpos($file, SITES) === 0)
			$file = 'SITES'.substr($file, strlen(SITES));
		elseif (strpos($file, ENGINE) === 0)
			$file = 'ENGINE'.substr($file, strlen(ENGINE));
		elseif (strpos($file, GEARS_FOLDER) === 0)
			$file = 'GEARS'.substr($file, strlen(GEARS_FOLDER));
		elseif (strpos($file, ROOT) === 0)
			$file = 'ROOT'.substr($file, strlen(ROOT));

		return $file;
	}

    public static function dump($var,$name='...') {

        $template = new Template('Dev.dump');


        $backtace = debug_backtrace();
        $template->call = '<span class="backtrace-call" style="white-space:nowrap;">
			Called from <code>'.self::path($backtace['file']).'</code>,
				line <code>'.$backtace['line'].'</code></span>';

        $template->dump = self::_dump($var,$name);

		$_recursion_marker = self::_recursion_marker();
		if ($hive =& self::_hive($dummy)) {
			foreach($hive as $i=>$bee){
				if (is_object($bee)) {
					unset($hive[$i]->$_recursion_marker);
					} else {
					unset($hive[$i][$_recursion_marker]);
					}
				}
			}

        return $template->render();
    }

    /**
     * Detects $var type and render it
     * @static
     * @param $var
     * @return string
     */
    protected static function _dump($var, $name='...') {
        $template = new Template('Dev.dump_type');
        $template->name = $name;
        switch($var) {
            case is_null($var):
                $template->type = 'NULL';
                $template->type_class = 'null';
                $template->element = '( NULL )';
                break;

            case is_float($var):
                $template->type = 'Float';
                $template->type_class = 'float';
                $template->element = $var;
                break;

            case is_integer($var):
                $template->type = 'Integer';
                $template->type_class = 'integer';
                $template->element = $var;
                break;

            case is_bool($var):
                $template->type = 'Boolean';
                $template->type_class = 'bool';
                $template->element = $var ? 'TRUE':'FALSE';
                break;

            case is_string($var):
                $template->type = 'String';
                $template->type_class = 'string';
                $template->element = $var;
                break;

            case is_resource($var):
                $template->type = 'Resource';
                $template->type_class = 'resource';
                $template->element = '( '.get_resource_type($var).' )';
                break;

            case is_array($var):
                $template->type = 'Array';
                $template->type_class = 'array';
                $template->element = '( '.count($var).' )';
                if(count($var)) $template->dump =& self::_vars($var);
                break;

            case is_object($var):
                $template->type = 'Object';
                $template->type_class = 'object';
                $template->element = '( '.get_class($var).' )';
                if(count($var)) $template->dump =& self::_vars($var);
                break;

            default:
                $template->type = 'Undefinied';
                $template->type_class = 'undefinied';
                $template->element = 'undefinied';
                break;

        }

        return $template->render();
    }

    /**
     * Process vars and nested rendering
     * @static
     * @param $data
     * @return string
     */
    private static function _vars($data){

		$_is_object = is_object($data);

		$_recursion_marker = self::_recursion_marker();
		$_r = (integer) ($_is_object) ? @$data->$_recursion_marker : @$data[$_recursion_marker];

		if ($_r > 0) return self::_recursion();

		self::_hive($data);

        $template = new Template('Dev.dump_nest');
        $template->elements = '';
        
        $keys = ($_is_object) ? array_keys(get_object_vars($data)) : array_keys($data);

	foreach($keys as $k) {

		// skip marker
		//
		if ($k === $_recursion_marker) {
			continue;
			}

		// get real value
		//
		if ($_is_object) {
			$v =& $data->$k;
			} else {
			$v =& $data[$k];
			}

		$template->elements .= self::_dump($v,$k);
	}

//        foreach($keys as $k) {
//
//            if ($k === $_recursion_marker) continue;
//
//            if ($_is_object){
//
//                //isset($data->$k) ? $v =& $data->$k : NULL;
//                if(isset($data->$k))
//                    $v =& $data->$k;
//                else
//                    continue;
//            }else
//                if(isset($data[$k]))
//                    $v =& $data[$k];
//                else
//                    continue;
//
//
//        }
        
        return $template->render();
	}

    private static function _recursion() {
        return 'recursion';
    }

    private static function _recursion_marker() {
        static $_recursion_marker;
        if (!isset($_recursion_marker))
            $_recursion_marker = uniqid('cogear');

        return $_recursion_marker;
    }

    private static function &_hive(&$bee) {
        static $_ = array();

        if (!is_null($bee)) {
            $_recursion_marker = self::_recursion_marker();
            (is_object($bee)) ? @($bee->$_recursion_marker++) : @($bee[$_recursion_marker]++);

            $_[0][] =& $bee;
            }

        return $_[0];
    }

    /**
     * Renders all environment variables
     * $_SERVER, $_GET\POST, Gears loaded, Includet files
     * @static
     * @return void
     */
    public static function environment() {
        $template = new Template('Dev.dump_env');
        return $template->render();
    }
    
    /**
     * @TODO is very bad written function, needs rework
	 * Returns an HTML string, highlighting a specific line of a file, with some
	 * number of lines padded above and below.
	 *
	 *     // Highlights the current line of the current file
	 *     echo Dev::source(__FILE__, __LINE__);
	 *
	 * @param   string   file to open
	 * @param   integer  line number to highlight
	 * @param   integer  number of padding lines
	 * @return  string   source of file
	 * @return  FALSE    file is unreadable
	 */
	public static function source($file, $line_number, $padding = 5)
	{
		if ( !$file OR !is_readable($file))
			// Continuing will cause errors
			return FALSE;

		// Open the file and set the line position
		$file = fopen($file, 'r');
		$line = 0;

		// Set the reading range
		$range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

		// Set the zero-padding amount for line numbers
		$format = '% '.strlen($range['end']).'d';

		$source = '';
		while (($row = fgets($file)) !== FALSE)
		{
			// Increment the line number
			if (++$line > $range['end'])
				break;

			if ($line >= $range['start'])
			{
				// Make the row safe for output
				$row = htmlspecialchars($row, ENT_NOQUOTES);
				// Trim whitespace and sanitize the row
				$row = '<span class="number">'.sprintf($format, $line).'</span> '.$row;

				if ($line === $line_number)
				{
					// Apply highlighting to this row
					$row = '<span class="line highlight">'.$row.'</span>';
				}
				else
				{
					$row = '<span class="line">'.$row.'</span>';
				}

				// Add to the captured source
				$source .= $row;
			}
		}

		// Close the file
		fclose($file);

		return '<pre class="source"><code>'.$source.'</code></pre>';
	}

	/**
     * @TODO is very bad written function, needs rework
	 * Returns an array of HTML strings that represent each step in the backtrace.
	 *
	 *     // Displays the entire current backtrace
	 *     echo implode('<br/>', Dev::trace());
	 *
	 * @param   string  path to debug
	 * @return  string
	 */
	public static function trace(array $trace = NULL)
	{
		$trace OR $trace = debug_backtrace();
		// Non-standard function calls
		$restricted_functions = array('include', 'include_once', 'require', 'require_once');

		$output = array();
		foreach ($trace as $step)
		{
			if (!isset($step['function'])) continue;

			if (isset($step['file']) AND isset($step['line'])) $source = self::source($step['file'], $step['line']);

			if (isset($step['file']))
			{
				$file = $step['file'];

				if (isset($step['line']))
				{
					$line = $step['line'];
				}
			}

			// function()
			$function = $step['function'];

			if (in_array($step['function'], $restricted_functions))
			{
				if (empty($step['args']))
				{
					// No arguments
					$args = array();
				}
				else
				{
					// Sanitize the file path
					$args = array($step['args'][0]);
				}
			}
			elseif (isset($step['args']))
			{
				if ( ! function_exists($step['function']) OR strpos($step['function'], '{closure}') !== FALSE)
				{
					// Introspection on closures or language constructs in a stack trace is impossible
					$params = NULL;
				}
				else
				{
					if (isset($step['class']))
					{
						if (method_exists($step['class'], $step['function']))
						{
							$reflection = new ReflectionMethod($step['class'], $step['function']);
						}
						else
						{
							$reflection = new ReflectionMethod($step['class'], '__call');
						}
					}
					else
					{
						$reflection = new ReflectionFunction($step['function']);
					}

					// Get the function parameters
					$params = $reflection->getParameters();
				}

				$args = array();

				foreach ($step['args'] as $i => $arg)
				{
					if (isset($params[$i]))
					{
						// Assign the argument by the parameter name
						$args[$params[$i]->name] = $arg;
					}
					else
					{
						// Assign the argument by number
						$args[$i] = $arg;
					}
				}
			}

			if (isset($step['class']))
			{
				// Class->method() or Class::method()
				$function = $step['class'].$step['type'].$step['function'];
			}

			$output[] = array(
				'function' => $function,
				'args'     => isset($args)   ? $args : NULL,
				'file'     => isset($file)   ? $file : NULL,
				'line'     => isset($line)   ? $line : NULL,
				'source'   => isset($source) ? $source : NULL,
			);

			unset($function, $args, $file, $line, $source);
		}

		return $output;
	}

}