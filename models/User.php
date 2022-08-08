<?php
    class User {
        public $id;
        public $user_nicename;
        public $user_email;
        public $user_url;
        public $user_registered;
        public $display_name;
        public $first_name;
        public $last_name;
        public $description;
        public $user_status;



        public function __construct() {
        }

        public static function getUserByID($index = Null) {
            $user;
            if($index == Null) {
                $index = $_GET['index'];
                $user = get_users(array(
                    'search' => $index
                ));
            } else {
                $user = get_users(array(
                    'search' => $index
                ));
            }
            return $user[0];

        }
        public static function getUsers() {
            $users = get_users(array(
                'role' => 'subscriber'
            ));

            return $users;
        }
    }
?>