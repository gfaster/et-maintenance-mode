<?php
declare(strict_types=1);
namespace EtFramework19\MaintenanceMode\Models;
class MaintenanceMode_Model {

    public static function IsInMaintenanceMode(): bool {
        $v =  get_site_option('etac_is_in_site_wide_maintenance_mode',false);
        return (bool) $v;
    }
    public static function GoIntoMaintenanceModeImmediately(): bool {
        return update_site_option('etac_is_in_site_wide_maintenance_mode',true);
    }
    public static function HaltMaintenanceModeImmediately(): bool {
        return update_site_option('etac_is_in_site_wide_maintenance_mode',false);
    }


}