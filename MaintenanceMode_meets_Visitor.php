<?php
declare(strict_types=1);
//namespace MaintenanceMode;

#use MaintenanceMode\Models\MaintenanceMode_Funcs;
#use MaintenanceMode\Models\MaintenanceMode_Model;
//use EtFramework19\Roles\CurrentUser;

class MaintenanceMode_meets_Visitor {
    public CONST NUM_SECONDS_TYPICAL_SHUTDOWN = 60;
    private CONST BACKGROUND_IMAGE_NAME = 'MaintenanceMode.jpg';

    public static function _Bootup(): void {
        //MaintenanceMode_Funcs::Shutdown_hard("temp", self::NUM_SECONDS_TYPICAL_SHUTDOWN, 'temp');
        if (MaintenanceMode_Model::IsInMaintenanceMode()) {
            // Maybe shutdown
            $doShutdown = self::DoWeShutOffThisPersonEvenThoughWeAreInMaintMode();
            if ($doShutdown) {
                if (self::doesDeserveHumanReadableMessage()) {
                    global $pagenow;
                    $message = 'Our website is currently undergoing scheduled maintenance. <br>This page will automatically reload when re-available.';
                } else {
                    $message = '';
                }
                if (is_multisite()) {
                    switch_to_blog(1);
                }
                $to = wp_get_upload_dir()['basedir'].DIRECTORY_SEPARATOR.self::BACKGROUND_IMAGE_NAME;
                if (! file_exists($to)) {
                    $from = __DIR__.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.self::BACKGROUND_IMAGE_NAME;

                    print "Please do something like this: cp \"$from\" \"$to\"";
                    exit;
                }


                $html = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'MaintenanceMode'.DIRECTORY_SEPARATOR.'down.html');
                $full_background_url = wp_get_upload_dir()['baseurl']."/".self::BACKGROUND_IMAGE_NAME;
                if (is_multisite()) {
                    restore_current_blog();
                }
                $html = str_replace('{{full_background_url}}', $full_background_url, $html);
                MaintenanceMode_Funcs::Shutdown_hard($html, self::NUM_SECONDS_TYPICAL_SHUTDOWN, $message);
            }
        }
    }
    private static function DoWeShutOffThisPersonEvenThoughWeAreInMaintMode(): bool {

        get_currentuserinfo();
        if (is_super_admin()) {
            return false; //allow admin to use site normally
        }
        if(class_exists('WP_CLI')) {
            return false; //being accessed from WP_CLI
        }
        return true;
//        if (CurrentUser::IsStStaff()) {
//            return false;
//        }
//        //jjr better login detection -begin-
//        # Motivation:
//        # 1) handle case of wp-admin/page=update.php?ILoveHacking&blah=wp-login.php
//        # 2) On standard wpengine.com multi-site install, could not login as site admin while in maint mode
//        #if ($wpdb->blogid == 1 && $this->urlend('wp-login.php')) return; //I told you *not* to log out, but you did anyway. duh!
//        global $pagenow;
//        if (is_multisite()) {
//            global $wpdb;
//            // restore this logic once the switch isn't at every site
//            // $isAtRootLogin = ($wpdb->blogid == 1 && $pagenow == 'wp-login.php');
//            $isAtRootLogin = ($pagenow == 'wp-login.php');
//        } else {
//            $isAtRootLogin = ($pagenow == 'wp-login.php');
//        }
//        if ($isAtRootLogin) {
//            return false; //I told you *not* to log out, but you did anyway. duh!
//        }
//        return true;
    }

    private static function urlend($end): bool {
        return substr($_SERVER['REQUEST_URI'], strlen($end)*-1) == $end;
    }
    private static function doesDeserveHumanReadableMessage(): bool {
        return (! self::urlend('feed/') && ! self::urlend('trackback/') && ! self::urlend('xmlrpc.php'));
    }
}