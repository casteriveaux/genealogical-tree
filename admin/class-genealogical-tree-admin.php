<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-admin.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function admin_menu()
    {
        add_menu_page(
            __( 'Genealogical Tree', 'genealogical-tree' ),
            __( 'Genealogical Tree', 'genealogical-tree' ),
            'manage_options',
            'genealogical-tree',
            array( $this, 'genealogical_tree_page' ),
            'dashicons-groups',
            40
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Add New', 'genealogical-tree' ),
            __( 'Add New', 'genealogical-tree' ),
            'manage_options',
            'post-new.php?post_type=member'
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Family Groups', 'genealogical-tree' ),
            __( 'Family Groups', 'genealogical-tree' ),
            'manage_categories',
            'edit-tags.php?taxonomy=family_group&post_type=member'
        );
        /*
        add_submenu_page( 'genealogical-tree', __( 'Settings', 'genealogical-tree' ), __( 'Settings', 'genealogical-tree' ), 'manage_options', 'genealogical-tree', array( $this, 'genealogical_tree_page' ) );
        */
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function genealogical_tree_page()
    {
        ?>
        <div class="wrap">
            <h2>Settings</h2>


        </div>
        <?php 
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function init_post_type_and_taxonomy()
    {
        $labels = array(
            'name'               => _x( 'Members', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Member', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Members', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Member', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'member', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Member', 'genealogical-tree' ),
            'new_item'           => __( 'New Member', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Member', 'genealogical-tree' ),
            'view_item'          => __( 'View Member', 'genealogical-tree' ),
            'all_items'          => __( 'All Members', 'genealogical-tree' ),
            'search_items'       => __( 'Search Members', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Members:', 'genealogical-tree' ),
            'not_found'          => __( 'No members found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No members found in Trash.', 'genealogical-tree' ),
        );
        $supports = array( 'title' );
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'genealogical-tree',
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'member',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
        );
        register_post_type( 'member', $args );
        $labels = array(
            'name'               => _x( 'Families', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Family', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Families', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Family', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'family', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Family', 'genealogical-tree' ),
            'new_item'           => __( 'New Family', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Family', 'genealogical-tree' ),
            'view_item'          => __( 'View Family', 'genealogical-tree' ),
            'all_items'          => __( 'All Families', 'genealogical-tree' ),
            'search_items'       => __( 'Search Families', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Families:', 'genealogical-tree' ),
            'not_found'          => __( 'No families found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No families found in Trash.', 'genealogical-tree' ),
        );
        $supports = array( 'title' );
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'family',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
        );
        register_post_type( 'family', $args );
        $labels = array(
            'name'                       => _x( 'Family Groups', 'genealogical-tree', 'genealogical-tree' ),
            'singular_name'              => _x( 'Family Group', 'taxonomy singular name', 'genealogical-tree' ),
            'search_items'               => __( 'Search Family Groups', 'genealogical-tree' ),
            'popular_items'              => __( 'Popular Family Groups', 'genealogical-tree' ),
            'all_items'                  => __( 'All Family Groups', 'genealogical-tree' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Family Group', 'genealogical-tree' ),
            'update_item'                => __( 'Update Family Group', 'genealogical-tree' ),
            'add_new_item'               => __( 'Add New Group', 'genealogical-tree' ),
            'new_item_name'              => __( 'New Group Name', 'genealogical-tree' ),
            'separate_items_with_commas' => __( 'Separate family group with commas', 'genealogical-tree' ),
            'add_or_remove_items'        => __( 'Add or remove family group', 'genealogical-tree' ),
            'choose_from_most_used'      => __( 'Choose from the most used family group', 'genealogical-tree' ),
            'not_found'                  => __( 'No family group found.', 'genealogical-tree' ),
            'menu_name'                  => __( 'Family Groups', 'genealogical-tree' ),
        );
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array(
            'slug' => 'family_group',
        ),
        );
        register_taxonomy( 'family_group', array( 'member', 'family' ), $args );
    }
    
    public function member_columns( $columns )
    {
        $columns['ID'] = __( 'ID', 'genealogical-tree' );
        $columns['born'] = __( 'Born', 'genealogical-tree' );
        $columns['title'] = __( 'Name', 'genealogical-tree' );
        return $columns;
    }
    
    public function member_sortable_columns( $columns )
    {
        $columns['born'] = 'born';
        $columns['taxonomy-family'] = 'family';
        return $columns;
    }
    
    public function member_posts_born_column( $column, $post_id )
    {
        switch ( $column ) {
            case 'born':
                $event = get_post_meta( $post_id, 'event', true );
                $date = '';
                if ( isset( $event['birt'] ) ) {
                    if ( $event['birt'][0] ) {
                        $date = $event['birt'][0]['date'];
                    }
                }
                echo  $date ;
                break;
            case 'ID':
                echo  $post_id ;
                break;
        }
    }
    
    public function member_born_orderby( $query )
    {
        $orderby = $query->get( 'orderby' );
        /*
        if( 'born' == $orderby ) {
            $query->set('meta_key','born');
            $query->set('orderby','meta_value');
        }
        */
        
        if ( 'family_group' == $orderby ) {
            $query->set( 'tax_query', array(
                'taxonomy' => 'family_group',
            ) );
            $query->set( 'orderby', 'meta_value' );
        }
    
    }
    
    /** 
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_member_info( $post_type )
    {
        add_meta_box(
            'genealogical-tree-member-meta-box',
            __( 'Member info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_member_info' ),
            'member',
            'normal',
            'high'
        );
    }
    
    /**
     * Register the .
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_family_info( $post_type )
    {
        add_meta_box(
            'genealogical-tree-family-meta-box',
            __( 'Family info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_family_info' ),
            'family',
            'normal',
            'high'
        );
    }
    
    /**
     * Register the
     *
     * @since    1.0.0
     */
    public function render_meta_box_family_info( $post )
    {
        //echo "<pre>";
        //$get_post_meta = get_post_meta($post->ID);
        //$get_post_meta['chill'] = get_post_meta($post->ID, 'chill');
        //print_r($get_post_meta);
        //echo "<pre>";;
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_info( $post )
    {
        $spouses = ( get_post_meta( $post->ID, 'spouses', true ) ? get_post_meta( $post->ID, 'spouses', true ) : array( array(
            'id' => null,
        ) ) );
        $full_name = get_post_meta( $post->ID, 'full_name', true );
        $given_name = get_post_meta( $post->ID, 'given_name', true );
        $surname = get_post_meta( $post->ID, 'surname', true );
        $father = get_post_meta( $post->ID, 'father', true );
        $mother = get_post_meta( $post->ID, 'mother', true );
        $event = get_post_meta( $post->ID, 'event', true );
        $phone = ( get_post_meta( $post->ID, 'phone', true ) ? get_post_meta( $post->ID, 'phone', true ) : $this->fake_ci() );
        $email = ( get_post_meta( $post->ID, 'email', true ) ? get_post_meta( $post->ID, 'email', true ) : $this->fake_ci() );
        $address = ( get_post_meta( $post->ID, 'address', true ) ? get_post_meta( $post->ID, 'address', true ) : $this->fake_ci() );
        $query = new WP_Query( array(
            'post_type'      => 'member',
            'posts_per_page' => -1,
        ) );
        $fathers = array();
        $mothers = array();
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                if ( get_post_meta( $member->ID, 'sex', true ) === 'M' ) {
                    array_push( $fathers, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) === 'F' ) {
                    array_push( $mothers, $member->ID );
                }
            }
        }
        $sex = get_post_meta( $post->ID, 'sex', true );
        if ( !$event ) {
            $event = array();
        }
        
        if ( !isset( $event['birt'] ) ) {
            $event['birt'][0] = $this->fake_birt_deat();
            if ( !current( $event['birt'] ) ) {
                $event['birt'][0] = $this->fake_birt_deat();
            }
        }
        
        
        if ( !isset( $event['deat'] ) ) {
            $event['deat'][0] = $this->fake_birt_deat();
            if ( !current( $event['deat'] ) ) {
                $event['deat'][0] = $this->fake_birt_deat();
            }
        }
        
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-member-info.php';
        //print_r('<pre>');
        //$get_post_meta = get_post_meta($post->ID);
        //$get_post_meta['spouses'] = get_post_meta($post->ID, 'spouses', true);
        //$get_post_meta['childof'] = get_post_meta($post->ID, 'childof', true);
        //$get_post_meta['event'] = get_post_meta($post->ID, 'event', true);
        //print_r($get_post_meta);
        //print_r('</pre>');
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function fake_birt_deat()
    {
        return array(
            'date'  => '',
            'place' => '',
        );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function fake_ci()
    {
        return array( ' ' );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function repear_full_name( $name )
    {
        return wp_strip_all_tags( trim( str_replace( array( '/', '\\' ), array( ' ', '' ), $name ) ) );
    }
    
    public function family_group_validation_notice_handler()
    {
        $errors = get_option( 'family_group_validation' );
        if ( $errors ) {
            echo  '<div class="error"><p>' . $errors . '</p></div>' ;
        }
        update_option( 'family_group_validation', false );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_member_info( $post_id )
    {
        if ( !isset( $_POST['_nonce_update_member_info_nonce'] ) ) {
            return $post_id;
        }
        $nonce = $_POST['_nonce_update_member_info_nonce'];
        if ( !wp_verify_nonce( $nonce, 'update_member_info_nonce' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        
        if ( 'member' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        $family_group = get_the_terms( $post, 'family_group' );
        
        if ( !$family_group ) {
            $errors = 'Whoops... you forgot to select family group.';
            update_option( 'family_group_validation', $errors );
            return;
        } else {
            update_option( 'family_group_validation', false );
        }
        
        $motherBefoerUpdate = get_post_meta( $post_id, 'mother', true );
        $fatherBefoerUpdate = get_post_meta( $post_id, 'father', true );
        $full_name = ( isset( $_POST['gt']['full_name'] ) ? sanitize_text_field( $_POST['gt']['full_name'] ) : null );
        $given_name = ( isset( $_POST['gt']['given_name'] ) ? sanitize_text_field( $_POST['gt']['given_name'] ) : null );
        $surname = ( isset( $_POST['gt']['surname'] ) ? sanitize_text_field( $_POST['gt']['surname'] ) : null );
        $mother = ( isset( $_POST['gt']['mother'] ) ? sanitize_text_field( $_POST['gt']['mother'] ) : null );
        $father = ( isset( $_POST['gt']['father'] ) ? sanitize_text_field( $_POST['gt']['father'] ) : null );
        $sex = ( isset( $_POST['gt']['sex'] ) ? sanitize_text_field( $_POST['gt']['sex'] ) : null );
        $event = ( isset( $_POST['gt']['event'] ) ? $_POST['gt']['event'] : array() );
        $phone = ( isset( $_POST['gt']['phone'] ) ? $_POST['gt']['phone'] : array() );
        $email = ( isset( $_POST['gt']['email'] ) ? $_POST['gt']['email'] : array() );
        $address = ( isset( $_POST['gt']['address'] ) ? $_POST['gt']['address'] : array() );
        $xcv = 0;
        foreach ( $event as $key1 => $value1 ) {
            
            if ( is_int( $key1 ) ) {
                foreach ( $value1 as $key2 => $value2 ) {
                    $event[$value2['type']][$xcv] = array(
                        'type'  => sanitize_text_field( $value2['type'] ),
                        'ref'   => sanitize_text_field( $value2['ref'] ),
                        'date'  => sanitize_text_field( $value2['date'] ),
                        'place' => sanitize_text_field( $value2['place'] ),
                    );
                }
                unset( $event[$key1] );
            }
            
            $xcv++;
        }
        foreach ( $phone as $key => $ph ) {
            $phone[$key] = sanitize_text_field( $ph );
        }
        foreach ( $email as $key => $em ) {
            $email[$key] = sanitize_email( $em );
        }
        foreach ( $address as $key => $addr ) {
            $address[$key] = sanitize_text_field( $addr );
        }
        update_post_meta( $post_id, 'full_name', $this->repear_full_name( $full_name ) );
        update_post_meta( $post_id, 'given_name', $given_name );
        update_post_meta( $post_id, 'surname', $surname );
        update_post_meta( $post_id, 'mother', $mother );
        update_post_meta( $post_id, 'father', $father );
        update_post_meta( $post_id, 'sex', $sex );
        update_post_meta( $post_id, 'event', $event );
        update_post_meta( $post_id, 'phone', $phone );
        update_post_meta( $post_id, 'email', $email );
        update_post_meta( $post_id, 'address', $address );
        if ( $sex === 'M' ) {
            $spouses = sanitize_text_field( $_POST['gt']['wife'] );
        }
        if ( $sex === 'F' ) {
            $spouses = sanitize_text_field( $_POST['gt']['husb'] );
        }
        if ( $spouses ) {
            if ( !current( $spouses )['id'] ) {
                $spouses = array();
            }
        }
        update_post_meta( $post_id, 'spouses', $spouses );
        $this->createOrUpdateFamily( $post_id, $spouses, $sex );
        $this->findOrCreateFamily( $father, $mother );
        $this->findOrCreateFamily( $father, null );
        $this->findOrCreateFamily( $mother, $father );
        $this->findOrCreateFamily( $mother, null );
        $this->attacDetachedFamily(
            $post_id,
            $father,
            $mother,
            $fatherBefoerUpdate,
            $motherBefoerUpdate
        );
    }
    
    /**
     * Register the
     *
     * @since    1.0.0
     */
    public function attacDetachedFamily(
        $post_id,
        $father,
        $mother,
        $fatherBefoerUpdate,
        $motherBefoerUpdate
    )
    {
        $query = new WP_Query( array(
            'post_type'      => 'family',
            'posts_per_page' => -1,
            'meta_query'     => array(
            'relation' => 'OR',
            array(
            'key'     => 'root',
            'value'   => $father,
            'compare' => '=',
        ),
            array(
            'key'     => 'spouse',
            'value'   => $father,
            'compare' => '=',
        ),
            array(
            'key'     => 'root',
            'value'   => $mother,
            'compare' => '=',
        ),
            array(
            'key'     => 'spouse',
            'value'   => $mother,
            'compare' => '=',
        ),
            array(
            'key'     => 'root',
            'value'   => $fatherBefoerUpdate,
            'compare' => '=',
        ),
            array(
            'key'     => 'spouse',
            'value'   => $fatherBefoerUpdate,
            'compare' => '=',
        ),
            array(
            'key'     => 'root',
            'value'   => $motherBefoerUpdate,
            'compare' => '=',
        ),
            array(
            'key'     => 'spouse',
            'value'   => $motherBefoerUpdate,
            'compare' => '=',
        ),
        ),
        ) );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $family ) {
                $chill = ( get_post_meta( $family->ID, 'chill', true ) ? get_post_meta( $family->ID, 'chill', true ) : array() );
                foreach ( $chill as $fkey => $value ) {
                    if ( $value === $post_id ) {
                        unset( $chill[$fkey] );
                    }
                }
                update_post_meta( $family->ID, 'chill', array_unique( $chill ) );
            }
        }
        if ( $father && $mother ) {
            $query = new WP_Query( array(
                'post_type'      => 'family',
                'posts_per_page' => -1,
                'meta_query'     => array(
                'relation' => 'OR',
                array(
                'relation' => 'AND',
                array(
                'key'     => 'root',
                'value'   => $father,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'value'   => $mother,
                'compare' => '=',
            ),
            ),
                array(
                'relation' => 'AND',
                array(
                'key'     => 'root',
                'value'   => $mother,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'value'   => $father,
                'compare' => '=',
            ),
            ),
            ),
            ) );
        }
        if ( $father && !$mother ) {
            $query = new WP_Query( array(
                'post_type'      => 'family',
                'posts_per_page' => -1,
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'root',
                'value'   => $father,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'compare' => 'NOT EXISTS',
            ),
            ),
            ) );
        }
        if ( !$father && $mother ) {
            $query = new WP_Query( array(
                'post_type'      => 'family',
                'posts_per_page' => 1,
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'root',
                'value'   => $mother,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'compare' => 'NOT EXISTS',
            ),
            ),
            ) );
        }
        $childof = array();
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $family ) {
                $chill = ( get_post_meta( $family->ID, 'chill', true ) ? get_post_meta( $family->ID, 'chill', true ) : array() );
                array_push( $chill, $post_id );
                update_post_meta( $family->ID, 'chill', array_unique( $chill ) );
                array_push( $childof, $family->ID );
            }
        }
        update_post_meta( $post_id, 'childof', array_unique( $childof ) );
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function createOrUpdateFamily( $root, $spouses, $sex )
    {
        if ( !$root ) {
            return null;
        }
        $this->findOrCreateFamily( $root, null );
        if ( !empty($spouses) ) {
            foreach ( $spouses as $key => $spouse ) {
                $spouse_spouses = ( get_post_meta( $spouse['id'], 'spouses', true ) ? get_post_meta( $spouse['id'], 'spouses', true ) : array() );
                array_push( $spouse_spouses, array(
                    'id' => $root,
                ) );
                $un_spouse = array();
                foreach ( $spouse_spouses as $element ) {
                    
                    if ( $element['id'] ) {
                        $hash = $element['id'];
                        $un_spouse[$hash] = $element;
                    }
                
                }
                update_post_meta( $spouse['id'], 'spouses', $un_spouse );
                $family_id = $this->findOrCreateFamily( $root, $spouse['id'] );
                $family_id = $this->findOrCreateFamily( $spouse['id'], $root );
                $family_id = $this->findOrCreateFamily( $spouse['id'], null );
            }
        }
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function findOrCreateFamily( $root, $spouse )
    {
        if ( !$root ) {
            return null;
        }
        $family_id = null;
        
        if ( $spouse ) {
            $query = new WP_Query( array(
                'post_type'      => 'family',
                'posts_per_page' => 1,
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'root',
                'value'   => $root,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'value'   => $spouse,
                'compare' => '=',
            ),
            ),
            ) );
            
            if ( !$query->posts ) {
                $family_id = wp_insert_post( array(
                    'post_title'   => get_the_title( $root ) . ' and ' . get_the_title( $spouse ),
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_author'  => 1,
                    'post_type'    => 'family',
                ) );
                update_post_meta( $family_id, 'root', $root );
                update_post_meta( $family_id, 'spouse', $spouse );
            } else {
                $family_id = current( $query->posts )->ID;
            }
        
        }
        
        
        if ( !$spouse ) {
            $query = new WP_Query( array(
                'post_type'      => 'family',
                'posts_per_page' => 1,
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'root',
                'value'   => $root,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'compare' => 'NOT EXISTS',
            ),
            ),
            ) );
            
            if ( !$query->posts ) {
                $family_id = wp_insert_post( array(
                    'post_title'   => get_the_title( $root ),
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_author'  => 1,
                    'post_type'    => 'family',
                ) );
                update_post_meta( $family_id, 'root', $root );
            } else {
                $family_id = current( $query->posts )->ID;
            }
        
        }
        
        return $family_id;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function get_family_group_name( $family_group_name )
    {
        
        if ( $family_group_name ) {
            $family_group_name = sanitize_text_field( $family_group_name );
            $term = term_exists( $family_group_name, 'family_group' );
            $suggestions = array();
            
            if ( 0 !== $term && null !== $term ) {
                $terms = get_terms( 'family_group', array(
                    'hide_empty' => false,
                ) );
                $terms_slug = array();
                foreach ( $terms as $key => $term ) {
                    array_push( $terms_slug, $term->slug );
                }
                $count = 0;
                $names_left = 1000;
                while ( $names_left > 0 ) {
                    $count++;
                    
                    if ( !in_array( sanitize_title( $family_group_name ) . '-' . $count, $terms_slug ) ) {
                        $suggestions[] = $family_group_name . ' ' . $count;
                        $names_left--;
                    }
                
                }
            } else {
                return $family_group_name;
            }
            
            return $suggestions[0];
        }
    
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function options_export()
    {
        ?>
        <div class="wrap">
            <h2>Export Ged</h2>

        </div>
        <?php 
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function create_family_group_free( $term_id )
    {
        
        if ( gt_fs()->is_not_paying() ) {
            $terms = get_terms( array(
                'taxonomy'   => 'family_group',
                'hide_empty' => false,
            ) );
            
            if ( count( $terms ) > 1 ) {
                wp_delete_term( $term_id, 'family_group' );
                echo  '<a href="' . gt_fs()->get_upgrade_url() . '">' . __( 'Upgrade Now!', 'genealogical-tree' ) . '</a> to create more family group' ;
                echo  '</section>' ;
                die;
            }
        
        }
    
    }
    
    /**
     *  Set the submenu as active/current while anywhere in your Custom Post Type (member)
     */
    public function set_family_group_current_menu( $parent_file )
    {
        global  $submenu_file, $current_screen, $pagenow ;
        
        if ( $current_screen->post_type == 'member' ) {
            if ( $pagenow == 'edit-tags.php' ) {
                $submenu_file = 'edit-tags.php?taxonomy=family_group&post_type=' . $current_screen->post_type;
            }
            $parent_file = 'genealogical-tree';
        }
        
        return $parent_file;
    }

}