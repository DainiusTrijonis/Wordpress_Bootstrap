<?php
    require_once('models/Subscription.php');
    class SubscriptionController {
        public static function getAll() {
            return Subscription::getAll();
        }
        public static function add() {
            $errors = SubscriptionController::validate();
            if(count($errors) == 0) {
                Subscription::add();
                return true;
            } else {
                $_SESSION['error'] = $errors;
                $_SESSION['old_inputs'] = $_POST;
                return false;
            }
        }
        public static function remove() {
            return Subscription::remove();
        }
        public static function update() {
            $errors = SubscriptionController::validate();
            if(count($errors) == 0) {
                Subscription::update();
                return true;
            } else {
                $_SESSION['error'] = $errors;
                $_SESSION['old_inputs'] = $_POST;
                return false;
            }
        }
        public static function get() {
            return Subscription::get();
        }

        public static function validate() {
            $errors = array();
            if(empty($_POST['title']) || strlen($_POST['title']) > 30) {
                $errors['title'] = 'Title must be less than 30 characters and not empty';
            }
            if(empty($_POST['notes']) || strlen($_POST['notes']) > 500) {
                $errors['notes'] = 'Notes must be less than 500 characters';
            }
            if(empty($_POST['expires_at'])) {
                $errors['expires_at'] = 'Expires at is required';
            }
            if(empty($_POST['status'])) {
                $errors['status'] = 'Status is required';
            }
            if(  !is_numeric($_POST['userID']) || $_POST['userID'] < 0 ) {
                $errors['userID'] = 'User is required';
            }
            if(  !is_numeric($_POST['packageID']) || $_POST['packageID'] < 0) {
                $errors['packageID'] = 'Package is required';
            }
            if(  $_POST['package_count'] < 0 || !is_numeric($_POST['package_count'])) {
                $errors['package_count'] = 'Package count is number and must be greater than 0';
            }
            return $errors;
        }
    }
?>