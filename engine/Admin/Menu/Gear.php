<?php
/**
 * Admin menu
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Admin_Menu_Gear extends Gear {
    protected $name = 'Admin Menu';
    protected $description = 'Add very handy menu to the top of the page for site admin.';
    /**
     * Init
     */
    public function init(){
        hook('request.admin',array($this,'render'));
    }
    /**
     * Render menu
     */
    public function render(){
        $cogear = getInstance();
        $menu = new Menu('admin.sidebar');
        $root = Url::gear('admin');
        $menu->{$root} = icon('dashboard','fugue').t('Dashboard');
        $menu->{$root.'gears'} = icon('gear','fugue').t('Gears');
        $menu->{$root.'site'} = icon('toolbox','fugue').t('Site');
        $menu->{$root.'site/clear_cache'} = icon('bin').t('Clear cache');
        prepend('sidebar',$menu->render('Admin_Menu.sidebar_menu'));
        css($this->folder.'/css/menu.css');
        $menu = new Menu('admin.top');
        Template::bindGlobal('top_menu',$menu);
    }
}
