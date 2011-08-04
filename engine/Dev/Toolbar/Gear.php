<?php
/**
 *  Developer Toolbar Gear
 *
 *
 *
 * @author		Nikolay Kostyurin <jilizart@gmail.com>
 * @copyright		Copyright (c) 2010, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Benchmark
 * @subpackage Toolbar
 * @version		$Id$
 */
class Dev_Toolbar_Gear extends Gear {

	protected $name = 'Developer toolbar';
    protected $description = '';
    protected $type = Gear::CORE;
    protected $order = -100;

    /**
     * Initialization
     */
    public function init() {
        parent::init();
        hook('done',array($this,'renderToolbar'));
    }
    

    public function renderToolbar() {
        $template = new Template('Dev_Toolbar.toolbar');
        $template->evalForm = $this->getEvalForm()->render();
        $template->gears = cogear()->gears;
        append('footer', $template->render());
    }

    public function index($action = '', $subaction = NULL) {
        echo var_dump($this->getEvalForm()->result());
        switch($action) {
            case 'eval':
                echo 'echo';
                if ($result = $this->getEvalForm()->result()) {
                   echo var_dump($result);
                }
                break;
        }
    }

    protected function getEvalForm() {
        return new Form('Dev_Toolbar.eval');
    }


    /**
     * Eval code;
     * @param $code
     * @return php eval result
     */
    protected function evalPhp($code) {
        return eval($code);
    }


}