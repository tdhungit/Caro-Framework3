<?php
// Auto Generate by TrueCustomer Builder

namespace TrueCustomer\Models;


use TrueCustomer\Models\Base\MainMenuBase;

class MainMenu extends MainMenuBase 
{
    public $audit = false;
    public $notify = false;
    public $action_controller_name = 'settings';
    public $action_list = 'main_menu';
    public $action_detail = 'detail_menu';
    public $action_edit = 'edit_menu';
    public $action_delete = 'delete_menu';

    public $children = array();

    public $default_menu = array();

    public function onConstruct()
    {
        parent::onConstruct();
        $this->setDefaultMenu();
    }

    public function setDefaultMenu()
    {
        $this->default_menu = array(
            array(
                'name' => 'Users',
                'icon' => 'fa fa-user',
                'controller' => 'users',
                'action' => 'index',
                'children' => array(
                    array(
                        'name' => 'View Users',
                        'icon' => 'fa fa-user',
                        'controller' => 'users',
                        'action' => 'index'
                    ),
                    array(
                        'name' => 'View Groups',
                        'icon' => 'fa fa-users',
                        'controller' => 'users',
                        'action' => 'groups'
                    ),
                    array(
                        'name' => 'View Roles',
                        'icon' => 'fa fa-user-secret',
                        'controller' => 'users',
                        'action' => 'roles'
                    )
                )
            ),
            array(
                'name' => 'Settings',
                'icon' => 'fa fa-cog',
                'controller' => 'settings',
                'action' => 'index',
                'children' => array(
                    array(
                        'name' => 'Settings',
                        'icon' => 'fa fa-legal',
                        'controller' => 'settings',
                        'action' => 'index'
                    ),
                    array(
                        'name' => 'Repair',
                        'icon' => 'fa fa-wrench',
                        'controller' => 'settings',
                        'action' => 'repair'
                    ),
                    array(
                        'name' => 'Clear Cache',
                        'icon' => 'fa fa-eraser',
                        'controller' => 'settings',
                        'action' => 'clear_cache'
                    ),
                    array(
                        'name' => 'Main Menu',
                        'icon' => 'fa fa-th-list',
                        'controller' => 'settings',
                        'action' => 'main_menu'
                    ),
                    array(
                        'name' => 'Dependency Fields',
                        'icon' => 'fa fa-object-group',
                        'controller' => 'settings',
                        'action' => 'dpfields'
                    ),
                )
            ),
            array(
                'name' => 'Model Builder',
                'icon' => 'fa fa-legal',
                'controller' => 'builder',
                'action' => 'index'
            ),
            array(
                'name' => 'Supports',
                'icon' => 'fa fa-info-circle',
                'controller' => 'supports',
                'action' => 'index',
                'children' => array(
                    array(
                        'name' => 'Icons',
                        'icon' => 'fa fa-genderless',
                        'controller' => 'supports',
                        'action' => 'icons'
                    ),
                )
            ),
        );
    }

    public function getArrayMainMenuFromDb()
    {
        $menus = array();
        $main_menus = $this->getMany(array(
            'conditions' => 'parent_id = 0',
            'order_by' => 'weight ASC'
        ));
        foreach ($main_menus as $main_menu) {
            /* @var $main_menu MainMenu */
            $menu = array(
                'name' => $main_menu->name,
                'icon' => $main_menu->icon,
                'controller' => $main_menu->controller,
                'action' => $main_menu->action,
                'query' => $main_menu->query,
                'url' => $main_menu->url,
                'target' => $main_menu->target,
                'children' => array()
            );

            $child_menus = $this->getMany("parent_id = {$main_menu->id}");
            foreach ($child_menus as $child_menu) {
                $menu['children'][] = array(
                    'name' => $child_menu->name,
                    'icon' => $child_menu->icon,
                    'controller' => $child_menu->controller,
                    'action' => $child_menu->action,
                    'query' => $child_menu->query,
                    'url' => $child_menu->url,
                    'target' => $child_menu->target
                );
            }

            $menus[] = $menu;
        }

        $menus = array_merge($menus, $this->default_menu);

        return $menus;
    }

    public function getArrayMainMenuFromCache()
    {
        if (file_exists(APP_PATH . '/app/config/layouts/main_menu.conf.php')) {
            return include APP_PATH . '/app/config/layouts/main_menu.conf.php';
        }

        return array();
    }

    public function cacheArrayMainMenu()
    {
        $menus = $this->getArrayMainMenuFromDb();
        // write cache
        $file_cache = fopen(APP_PATH . '/app/config/layouts/main_menu.conf.php', 'w');
        fwrite($file_cache, "<?php\n\nreturn " . var_export($menus, true) . ";\n");
        fclose($file_cache);
    }

    public function getMainMenu()
    {
        $menusObj = array();

        $menus = $this->getArrayMainMenuFromCache();
        foreach ($menus as $menu) {
            $menuObj = new MainMenu($menu);
            $menuObj->children = array();
            if (!empty($menu['children'])) {
                foreach ($menu['children'] as $child) {
                    $menuObj->children[] = new MainMenu($child);
                }
            }
            $menusObj[] = $menuObj;
        }

        return $menusObj;
    }

    public function hasPermission()
    {
        if ($this->url) {
            return true;
        }

        if ($this->auth->isAccess($this->controller, $this->action)) {
            return true;
        }

        return false;
    }

    public function hasChild()
    {
        if (empty($this->children)) {
            return false;
        }

        return true;
    }

    public function getUrl()
    {
        if ($this->url) {
            return $this->url;
        }

        return '/' . $this->controller . '/' . $this->action . '/' . $this->query;
    }

    public function isActive($current_controller, $parent = true, $current_action = '')
    {
        if ($parent == true && $this->controller == $current_controller) {
            return true;
        }

        if ($parent == false && $this->controller == $current_controller && $this->action == $current_action) {
            return true;
        }

        return false;
    }
}