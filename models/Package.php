<?php
    class Package {
        public $id;
        public $title;
        public $notes;
        public $days;
        public $price;


        public function __construct( $id, $title, $notes, $days, $price ) {
            $this->id = $id;
            $this->title = $title;
            $this->notes = $notes;
            $this->days = $days;
            $this->price = $price;
        }

        public static function getPackages() {
            $query = new WP_Query(array(
                'post_type' => 'packages',
            ));
            $packages = array();
            foreach ($query->posts as $post) {
                $packages[] = new Package(
                    $post->ID,
                    $post->post_title,
                    $post->post_content,
                    get_field('days', $post->ID),
                    get_field('price', $post->ID)
                );
            }
            return $packages;
        }
        public static function getPackageByID($index = Null) {
            $query;
            if($index == Null) {
                $query = new WP_Query(array(
                    'post_type' => 'packages',
                    'p' => $_GET['index']
                ));

            } else {
                $query = new WP_Query(array(
                    'post_type' => 'packages',
                    'p' => $index
                ));
            }
            $package = new Package(
                $query->posts[0]->ID,
                $query->posts[0]->post_title,
                $query->posts[0]->post_content,
                get_field('days', $query->posts[0]->ID),
                get_field('price', $query->posts[0]->ID),
            );
            return $package;
        }
    }
?>