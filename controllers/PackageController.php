<?php
    require_once('models/Package.php');
    class PackageController {
        public static function getPackages() {
            return Package::getPackages();
        }
        public static function getPackageByID() {
            return Package::getPackageByID();
        }
    }
?>