<?php

/**
 * Router 
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2010, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Router {

    /**
     * Routes
     *
     * @var array
     */
    private $routes = array(
    );
    /**
     * URI — path or URL after domain address.
     *
     * @var string
     */
    protected $uri;
    /**
     * URI segments
     *
     * @var array
     */
    private $segments = array();
    /**
     * Arguments — filtered segments
     * 
     * @var array
     */
    private $args = array();
    /**
     * Matches
     */
    protected $matches = array();
    /**
     * Flag indicates if router has run
     * 
     * @var boolean 
     */
    private $has_run;
    /**
     * Route expression transformation variables
     *
     * @array
     */
    private $rules = array(
        'from' => array(
            ':any',
            ':maybe',
            ':digit',
            ':alpha',
            ':word',
            ':index',
        ),
        'to' => array(
            '(.+?)',
            '(.*?)',
            '([\d]+)',
            '([a-z]+)',
            '([\w-_]+)',
            '\s*',
        )
    );
    /**
     * Url delimiter
     *
     * @const
     */
    const DELIM = '/';

    /**
     * Constructor
     */
    public function __construct() {
        $cogear = getInstance();
        $this->uri = $this->sanitizePath($cogear->request->get('PATH_INFO'));
        $this->segments = $this->parseSegments($this->uri);
        $cogear->hook('ignite',array($this,'run'));
    }

    /**
     * Sanitize path
     *
     * @param	string	$path
     */
    public function sanitizePath($path) {
        $cogear = getInstance();
        // Sanitize unwanted data from the path
        $path = preg_replace('#[^' . config('permitted_uri_chars','\w-_.') . self::DELIM . ']+#iu', '', $path);
        return trim($path, '/');
    }

    /**
     * Parse path segments
     *
     * @param	string	$path
     * @return	string
     */
    private function parseSegments($path) {
        $cogear = getInstance();
        // Explode uri into pieces, but previosely trim it from aside delimiters and whitespaces
        return $this->segments = preg_split('#[\s' . preg_quote(self::DELIM) . ']+#', $path, -1, PREG_SPLIT_NO_EMPTY);
    }
    /**
     * Add route
     *
     * @param string $route
     * @param callback $callback
     */
    public function addRoute($route, $callback, $rewrite = FALSE) {
        if($rewrite OR !isset($this->routes[$route])){
            $this->routes[$route] = $callback;
        }
    }
    /**
     * Get uri
     * 
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }
    /**
     * Get arguments
     * 
     * @return array
     */
    public function getArgs(){
        return $this->args;
    }
    /**
     * Get route matches
     * 
     * @return array
     */
    public function getMatches(){
        return $this->matches;
    }
    /**
     * Get segments
     *
     * @param   int     $num
     * @return array|string
     */
    public function segments($num = NULL){
        return $num ? (isset($this->segments[$num]) ? $this->segments[$num] : NULL) : $this->segments;
    }
    /**
     * Run dispatched request
     */
    public function run() {
        if($this->has_run) return;
        $cogear = getInstance();
        foreach ($this->routes as $route => $callback) {
            $route = str_replace(
                            $this->rules['from'],
                            $this->rules['to'],
                            $route);
            $clean_route = $route;
            if (strpos($route, '^') === FALSE) {
                $route = '^' . $route;
            }
            if (strpos($route, '$') === FALSE) {
                $route .= '$';
            }
            $regexp = '#' . $route . '#isU';
            if (preg_match($regexp, $this->uri,$this->matches)) {
                $args = array();
                if(is_array($callback) && sizeof($callback) > 2){
                    $args = array_slice($callback, 2);
                    $callback = array($callback[0],$callback[1]);
                }
                $root = substr($clean_route,0,strpos($clean_route,'('));
                $exclude = strpos($root,self::DELIM) ? preg_split(self::DELIM, $root, -1, PREG_SPLIT_NO_EMPTY) : (array) $root;
                $this->args = array_merge($args,array_diff_assoc($this->segments,$exclude));
                // We have a nice method in hooks to prepare callback
                if($callback = Cogear::prepareCallback($callback)){
                    method_exists($callback[0],'request') && call_user_func(array($callback[0],'request'));
                    call_user_func_array($callback,$this->args);
                    $this->has_run = TRUE;
                    return;
                }
            }
        }
        $this->exec(array($cogear->errors,'_404'));
        return;
    }
    /**
     * Execute callback
     * 
     */
    public function exec($callback,$args = array()){
        if(is_callable($callback) && $result = call_user_func_array($callback,$args)){
            $this->has_run = TRUE;
            return $result;
        }
        return NULL;
    }

}