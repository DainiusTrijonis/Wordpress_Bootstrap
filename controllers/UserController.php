<?php
    require_once('models/User.php');
    class UserController {
        public static function getUsers() {
            return User::getUsers();
        }
        public static function getUserByID() {
            return User::getUserByID();
        }
    }
?>