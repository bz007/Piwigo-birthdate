<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

defined('BIRTHDATE_ID') or define('BIRTHDATE_ID', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . BIRTHDATE_ID . '/include/install.inc.php');

/**
 * plugin installation
 *
 * perform here all needed step for the plugin installation
 * such as create default config, add database tables, 
 * add fields to existing tables, create local folders...
 */
function plugin_install() 
{
  birthdate_install();
  define('birthdate_installed', true);
}

/**
 * plugin activation
 *
 * this function is triggered adter installation, by manual activation
 * or after a plugin update
 * for this last case you must manage updates tasks of your plugin in this function
 */
function plugin_activate()
{
  if (!defined('birthdate_installed')) // a plugin is activated just after its installation
  {
    birthdate_install();
  }
}

/**
 * plugin unactivation
 *
 * triggered before uninstallation or by manual unactivation
 */
function plugin_unactivate()
{
}

/**
 * plugin uninstallation
 *
 * perform here all cleaning tasks when the plugin is removed
 * you should revert all changes made by plugin_install()
 */
function plugin_uninstall() 
{
  // delete field
  pwg_query('ALTER TABLE `' . TAGS_TABLE . '` DROP COLUMN `birthdate`;');
}

?>