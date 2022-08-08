<?php
    //import models
    require_once('Package.php');
    require_once('User.php');

    class Subscription {
        public $id;
        public $title;
        public $notes;
        public $created_at;
        public $last_updated_at;
        public $expires_at;
        public $status;
        public $user;
        public $package;
        public $package_count;

        public function __construct($id, $title, $notes, $created_at, $last_updated_at, $expires_at, $status, $user, $package, $package_count) {
            $this->id = $id;
            $this->title = $title;
            $this->notes = $notes;
            $this->created_at = $created_at;
            $this->last_updated_at = $last_updated_at;
            $this->expires_at = $expires_at;
            $this->status = $status;
            $this->user = $user;
            $this->package = $package;
            $this->package_count = $package_count;
        }

        public static function getAll() {
            $query = new WP_Query(array(
                'post_type' => 'subscriptions',
                'posts_per_page' => -1,
            ));
            $subscriptions = array();
            foreach ($query->posts as $post) {
                $subscriptions[] = new Subscription(
                    $post->ID,
                    $post->post_title,
                    $post->post_content,
                    get_field('created_at', $post->ID),
                    get_field('last_updated_at', $post->ID),
                    get_field('expires_at', $post->ID),
                    get_field('status', $post->ID),
                    User::getUserByID(get_field( 'user', $post->ID)),
                    Package::getPackageByID(get_field('package', $post->ID)),
                    get_field('package_count', $post->ID)
                );
            }

            return $subscriptions;
        }
        public static function get($index = Null) {
            $query;
            if($index == Null) {
                $query = new WP_Query(array(
                    'post_type' => 'subscriptions',
                    'p' => $_GET['index']
                ));
            } else {
                $query = new WP_Query(array(
                    'post_type' => 'subscriptions',
                    'p' => $index
                ));
            }

            $subscription = new Subscription(
                $query->posts[0]->ID,
                $query->posts[0]->post_title,
                $query->posts[0]->post_content,
                get_field('created_at', $query->posts[0]->ID),
                get_field('last_updated_at', $query->posts[0]->ID),
                get_field('expires_at', $query->posts[0]->ID),
                get_field('status', $query->posts[0]->ID),
                User::getUserByID(get_field('user', $query->posts[0]->ID)),
                Package::getPackageByID(get_field('package', $query->posts[0]->ID)),
                get_field('package_count', $query->posts[0]->ID)
            );
            return $subscription;
        }

        public static function add() {
            $user = User::getUserByID($_POST['userID']);
            $package = Package::getPackageByID($_POST['packageID']);


            $subscription = new Subscription(
                null,
                $_POST['title'],
                $_POST['notes'],
                date('Y-m-d'),
                date('Y-m-d'),
                $_POST['expires_at'],
                $_POST['status'],
                $user,
                $package,
                $_POST['package_count']
            );


            wp_insert_post(array(
                'post_title' => $subscription->title,
                'post_content' => $subscription->notes,
                'post_type' => 'subscriptions',
                'post_status' => 'publish',
                'meta_input' => array(
                    'created_at' => $subscription->created_at,
                    'last_updated_at' => $subscription->last_updated_at,
                    'expires_at' => $subscription->expires_at,
                    'status' => $subscription->status,
                    'user' => $subscription->user->id,
                    'package' => $subscription->package->id,
                    'package_count' => $subscription->package_count
                ) 
            ));
            return $subscription;
        }

        
        public static function remove() {
            wp_delete_post($_POST['index']);
        }
        public static function update() {
            wp_update_post(array(
                'ID' => $_POST['index'],
                'post_title' => $_POST['title'],
                'post_content' => $_POST['notes'],
                'post_type' => 'subscriptions',
                'post_status' => 'publish',
                'meta_input' => array(
                    'created_at' => $_POST['created_at'],
                    'last_updated_at' => date('Y-m-d'),
                    'expires_at' => $_POST['expires_at'],
                    'status' => $_POST['status'],
                    'user' => $_POST['userID'],
                    'package' => $_POST['packageID'],
                    'package_count' => $_POST['package_count']
                ) 
            ));
            return true;
        }
    }
?>