<?php
namespace Genealogical_Tree\Includes;

/**
 * The api functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */
/**
 * The api functionality of the plugin.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Api
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function register_rest_route_member_ind()
    {
        register_rest_route( 'genealogical-tree/v1', '/member/indi_(?P<id>\\d+).js', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'route_member_ind' ),
        ) );
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function register_rest_route_family_fam()
    {
        register_rest_route( 'genealogical-tree/v1', '/family/fam_(?P<id>\\d+).js', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'route_family_fam' ),
        ) );
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function register_rest_route_member_popover()
    {
        register_rest_route( 'genealogical-tree/v1', '/member/popover/(?P<id>\\d+)', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'my_awesome_func_3' ),
        ) );
    }
    
    public function f_image( $id )
    {
        return null;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function get_fam_ref_id( $object_id )
    {
        $fam = array();
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'meta_query'     => array( array(
            'key'     => 'root',
            'value'   => $object_id,
            'compare' => '=',
        ) ),
        ) );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $family ) {
                if ( get_post_meta( $family->ID, 'chill', true ) || get_post_meta( $family->ID, 'spouse', true ) ) {
                    array_push( $fam, $family->ID );
                }
            }
        }
        return $fam;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function route_member_ind( $obj )
    {
        $object_id = $obj['id'];
        $sex = get_post_meta( $object_id, 'sex', true );
        $fam = $this->get_fam_ref_id( $object_id );
        $event = array( (object) array(
            'type'  => 'image',
            'value' => (object) array(
            'event' => array( (object) array(
            'type' => 'info',
            'ref'  => '',
        ), (object) array(
            'type'  => 'image',
            'value' => $this->f_image( $object_id ),
        ), (object) array(
            'type'  => 'image',
            'value' => $this->f_image( $object_id ),
        ) ),
        ),
        ) );
        if ( $tree_event ) {
            foreach ( $tree_event as $key => $value ) {
                array_push( $event, (object) $value );
            }
        }
        $event = $this->getTreeEvents( $object_id );
        $data = (object) array(
            'root' => (object) array(
            'header' => (object) array(
            'generator' => 'gingell json',
            'version'   => '1.0.0',
        ),
            'indi'   => (object) array(
            'id'       => $object_id,
            'sex'      => $sex,
            'fullname' => ( get_post_meta( $object_id, 'full_name', true ) ? get_post_meta( $object_id, 'full_name', true ) : 'Unknown' ),
            'name'     => (object) array(
            'first' => ( get_post_meta( $object_id, 'full_name', true ) ? get_post_meta( $object_id, 'full_name', true ) : 'Unknown' ),
            'last'  => '',
        ),
            'childof'  => current( get_post_meta( $object_id, 'childof', true ) ),
            'fam'      => $fam,
            'event'    => $event,
        ),
        ),
        );
        //print_r($data);
        return $data;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function getFamilyWithPartner( $root, $spouse )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => 1,
            'meta_query'     => array(
            'relation' => 'OR',
            array(
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
            array(
            'relation' => 'AND',
            array(
            'key'     => 'root',
            'value'   => $spouse,
            'compare' => '=',
        ),
            array(
            'key'     => 'spouse',
            'value'   => $root,
            'compare' => '=',
        ),
        ),
        ),
        ) );
        return current( $query->posts )->ID;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function getFamilyWithoutPartner( $root )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
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
        foreach ( $query->posts as $key => $family ) {
            if ( get_post_meta( $family->ID, 'chill', true ) ) {
                return $family->ID;
            }
        }
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function route_family_fam( $obj )
    {
        $object_id = $obj['id'];
        $root = get_post_meta( $object_id, 'root', true );
        $spouse = get_post_meta( $object_id, 'spouse', true );
        
        if ( get_post_meta( $root, 'sex', true ) === 'M' ) {
            $husb = $root;
            $wife = $spouse;
        }
        
        
        if ( get_post_meta( $root, 'sex', true ) === 'F' ) {
            $husb = $spouse;
            $wife = $root;
        }
        
        $children = get_post_meta( $object_id, 'chill', true );
        $childs = array();
        if ( $children ) {
            foreach ( $children as $key => $child ) {
                $child_event = $this->getTreeEvents( $child );
                $childs[$child] = array(
                    'sex'   => get_post_meta( $child, 'sex', true ),
                    'event' => $child_event,
                    'image' => $this->f_image( $child ),
                    'name'  => (object) array(
                    'first' => ( get_post_meta( $child, 'full_name', true ) ? get_post_meta( $child, 'full_name', true ) : 'Unknown' ),
                    'last'  => '',
                ),
                    'ref'   => $child,
                );
            }
        }
        $husb_event = array( (object) array() );
        $husb_tree_event = $this->getTreeEvents( $husb );
        foreach ( $husb_tree_event as $key => $event ) {
            array_push( $husb_event, (object) $event );
        }
        $wife_event = array( (object) array() );
        $wife_tree_event = $this->getTreeEvents( $wife );
        foreach ( $wife_tree_event as $key => $wife_value ) {
            array_push( $wife_event, (object) $wife_value );
        }
        $husb_name = get_post_meta( $husb, 'full_name', true );
        $husbo = (object) array(
            'event' => $husb_event,
            'image' => $this->f_image( $husb ),
            'name'  => (object) array(
            'first' => ( $husb_name ? $husb_name : 'Unknown' ),
            'last'  => '',
        ),
            'ref'   => $husb,
            'sex'   => 'M',
        );
        $wife_name = get_post_meta( $wife, 'full_name', true );
        $wifeo = (object) array(
            'event' => $wife_event,
            'image' => $this->f_image( $wife ),
            'name'  => (object) array(
            'first' => ( $wife_name ? $wife_name : 'Unknown' ),
            'last'  => '',
        ),
            'ref'   => $wife,
            'sex'   => 'F',
        );
        $return = (object) array(
            'root' => (object) array(
            'header' => (object) array(
            'generator' => 'gingell json',
            'version'   => '1.0.0',
        ),
            'fam'    => (object) array(
            'id' => $object_id,
        ),
        ),
        );
        $return->root->fam->husb = $husbo;
        $return->root->fam->wife = $wifeo;
        if ( $children ) {
            $return->root->fam->children = $childs;
        }
        //print_r($return);
        return $return;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function getTreeEvents( $member )
    {
        $member_event = array( (object) array() );
        $member_spouses = get_post_meta( $member, 'spouses', true );
        $singleparent = $this->getFamilyWithoutPartner( $member );
        $member_partner_events = null;
        if ( $member_spouses ) {
            foreach ( $member_spouses as $key => $member_spouse ) {
                $member_partner_events[$key] = array(
                    'type'  => 'partner',
                    'ref'   => $this->getFamilyWithPartner( $member, $member_spouse['id'] ),
                    'image' => $this->f_image( $member ),
                );
            }
        }
        if ( $singleparent ) {
            $member_partner_events['singleparent'] = array(
                'type'  => 'partner',
                'ref'   => $singleparent,
                'image' => $this->f_image( $member ),
            );
        }
        if ( $member_partner_events ) {
            foreach ( $member_partner_events as $key => $event ) {
                array_push( $member_event, (object) $event );
            }
        }
        $events = get_post_meta( $member, 'event', true );
        if ( $events ) {
            foreach ( $events as $key => $event_single ) {
                if ( $key === 'birt' ) {
                    if ( current( $event_single ) ) {
                        if ( current( $event_single )['date'] ) {
                            array_push( $member_event, array(
                                'type' => 'birt',
                                'date' => (object) array(
                                'value' => current( $event_single )['date'],
                            ),
                            ) );
                        }
                    }
                }
                if ( $key === 'deat' ) {
                    if ( current( $event_single ) ) {
                        if ( current( $event_single )['date'] ) {
                            array_push( $member_event, array(
                                'type' => 'deat',
                                'date' => (object) array(
                                'value' => current( $event_single )['date'],
                            ),
                            ) );
                        }
                    }
                }
            }
        }
        return $member_event;
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function get_event_info( $obj_id, $type, $array )
    {
        $event = get_post_meta( $obj_id, 'event', true );
        if ( !empty($event[$type]) ) {
            
            if ( $array === true ) {
                return current( $event[$type] );
            } else {
                return $event[$type];
            }
        
        }
    }
    
    /**
     * Register the 
     *
     * @since    1.0.0
     */
    public function my_awesome_func_3( $obj )
    {
        $obj_id = null;
        if ( isset( $obj['id'] ) ) {
            $obj_id = $obj['id'];
        }
        if ( !$obj_id ) {
            return null;
        }
        $permalink = get_the_permalink( $obj_id );
        $birt = (object) $this->get_event_info( $obj_id, 'birt', true );
        $deat = (object) $this->get_event_info( $obj_id, 'deat', true );
        $html = '
		<div>
			<p style="margin-bottom:8px;"><b>' . get_post_meta( $obj_id, 'full_name', true ) . '</b></p>';
        
        if ( isset( $birt->date ) || isset( $birt->place ) ) {
            $html .= '<p style="margin-bottom:8px">Born : ';
            if ( isset( $birt->date ) ) {
                $html .= $birt->date;
            }
            
            if ( isset( $birt->place ) ) {
                $html .= '<br>';
                $html .= $birt->place;
            }
            
            $html .= '</p>';
        }
        
        
        if ( isset( $deat->date ) || isset( $deat->place ) ) {
            $html .= '<p style="margin-bottom:8px">Died : ';
            if ( isset( $deat->date ) ) {
                $html .= $deat->date;
            }
            
            if ( isset( $deat->place ) ) {
                $html .= '<br>';
                $html .= $deat->place;
            }
            
            $html .= '</p>';
        }
        
        $html .= '<div class="foo-btn">
				<a href="' . $permalink . '" class="btn btn-primary btn-block" rel="content">More</a>
				<a href="./?tree=' . $obj_id . '" class="btn btn-secondary btn-block" rel="content">Tree</a>
			</div>
		</div>';
        return $html;
    }

}