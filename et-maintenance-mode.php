<?php
/**
 * Plugin Name: -ET-Maintenance-Mode
 * Plugin URI: https://github.com/gfaster/et-maintenance-mode
 * Description: A WordPress plugin to facilitate slow updates
 * Text Domain: et-maintenance-mode
 * Version: 0.1
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Author: Gavin Rohrer, JJ Rohrer
 */



require_once (__DIR__."/Models/MaintenanceMode_Model.php");
require_once (__DIR__."/Models/MaintenanceMode_Funcs.php");
require_once (__DIR__."/MaintenanceMode_meets_Visitor.php");
# add_action('init', [\MaintenanceMode\Models\MaintenanceMode_meets_Visitor::class,'_Bootup'], 1);
add_action('init', function() {
    MaintenanceMode_meets_Visitor::_Bootup();
    }, 1);

if ( class_exists( 'WP_CLI' ) ) {
    WP_CLI::add_command('mm_enter', function ($args) {
        MaintenanceMode_Model::GoIntoMaintenanceModeImmediately();
    });
    WP_CLI::add_command('mm_exit', function ($args) {
        MaintenanceMode_Model::HaltMaintenanceModeImmediately();
    });
}
