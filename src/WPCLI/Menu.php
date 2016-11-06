<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\CLI;
use PressCLI\Option\Configuration;

trait Menu
{
    /**
     * Gets the menu id from the menu name.
     *
     * @param  string $name The menu name.
     *
     * @return string       The menu id.
     */
    protected static function getMenuIdFromName($name)
    {
        $id = str_replace(' ', '-', $name);
        $id = preg_replace('/[^A-Za-z0-9-_]/um', '', $id);
        $id = strtolower($id);

        return $id;
    }

    /**
     * Creates all of the configuration menus.
     */
    public static function menuCreateAll()
    {
        $config = Configuration::get();
        $menus = isset($config['menus']) ? $config['menus'] : [];
        foreach ($menus as $menu) {
            $name = $menu['name'];
            $location = $menu['location'];
            $menu_id = self::getMenuIdFromName($name);

            // Create the menu.
            self::menuCreate($name);

            // Assign the menu to its location.
            self::menuLocationAssign($menu_id, $location);
        }
    }

    /**
     * Creates a menu.
     *
     * @param  string $name The menu name.
     */
    public static function menuCreate($name)
    {
        CLI::execCommand('menu', ['create', "'{$name}'"]);
    }

    /**
     * Assigns a menu to a location by menu id.
     *
     * @param  string $menu_id  The menu id to assign.
     * @param  string $location The menu location to assign.
     */
    public static function menuLocationAssign($menu_id, $location)
    {
        CLI::execCommand('menu', ['location', 'assign', $menu_id, $location]);
    }
}
