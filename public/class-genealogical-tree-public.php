<?php

namespace Genealogical_Tree\Genealogical_Tree_Public;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/public
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Public
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
     * Register the stylesheets for the public-facing side of the site.
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
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-public.min.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
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
        wp_enqueue_script( 'underscore' );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-public.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        $gt_plan = 'free';
        $gtObj = array(
            'gt_dir_url'  => GENEALOGICAL_TREE_DIR_URL,
            'gt_dir_path' => GENEALOGICAL_TREE_DIR_PATH,
            'gt_site_url' => site_url(),
            'gt_rest_url' => rest_url(),
            'gt_ajax_url' => admin_url( 'admin-ajax.php' ),
            'gt_plan'     => $gt_plan,
        );
        wp_localize_script( $this->plugin_name, 'gtObj', $gtObj );
    }
    
    /**
     * Get siblings by ID
     *
     * @since    1.0.0
     */
    public function get_siblings( $post_id )
    {
        $siblings = array();
        $father = get_post_meta( $post_id, 'father', true );
        $mother = get_post_meta( $post_id, 'mother', true );
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => 1,
            'meta_query'     => array(
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
        ) );
        
        if ( $query->posts ) {
            $family = current( $query->posts );
            $siblings = get_post_meta( $family->ID, 'chill', true );
        }
        
        return $siblings;
    }
    
    /**
     * Get childrens by father ID or mother ID
     *
     * @since    1.0.0
     */
    public function check_childrens( $root )
    {
        $chill = array();
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => 1,
            'meta_query'     => array( array(
            'key'     => 'root',
            'value'   => $root,
            'compare' => '=',
        ) ),
        ) );
        
        if ( $query->posts ) {
            $family = current( $query->posts );
            $chill = get_post_meta( $family->ID, 'chill', true );
        }
        
        return $chill;
    }
    
    /**
     * Get childrens by father ID and mother ID
     *
     * @since    1.0.0
     */
    public function get_childrens( $root, $spouse )
    {
        $chill = array();
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
            'value'   => $spouse,
            'compare' => '=',
        ),
        ),
        ) );
        if ( !$spouse ) {
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
        }
        
        if ( $query->posts ) {
            $family = current( $query->posts );
            $chill = get_post_meta( $family->ID, 'chill', true );
        }
        
        return $chill;
    }
    
    /**
     * Member information By ID
     *
     * @since    1.0.0
     */
    public function single_member_info( $post_id )
    {
        $html = '';
        if ( !$post_id ) {
            return $html;
        }
        if ( !get_post( $post_id ) ) {
            return $html;
        }
        $gt_family_group = get_the_terms( $post_id, 'gt-family-group' );
        $tree_link = '';
        foreach ( $gt_family_group as $key => $term ) {
            $tree_link .= '<a href="' . get_the_permalink( get_term_meta( $term->term_id, 'tree_page', true ) ) . '?tree=' . $post_id . '"><img style="display:inline-block;" src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/chart_organisation.png"></a>';
        }
        $full_name = get_post_meta( $post_id, 'full_name', true );
        $event = get_post_meta( $post_id, 'event', true );
        $mother_id = get_post_meta( $post_id, 'mother', true );
        $father_id = get_post_meta( $post_id, 'father', true );
        $mother = get_post_meta( $mother_id, 'full_name', true );
        $father = get_post_meta( $father_id, 'full_name', true );
        $spouses = ( get_post_meta( $post_id, 'spouses', true ) ? get_post_meta( $post_id, 'spouses', true ) : array() );
        
        if ( !$spouses ) {
            $check_childrens = $this->check_childrens( $post_id );
            if ( $check_childrens ) {
                $spouses[0] = array(
                    'id' => 0,
                );
            }
        }
        
        $spouse_html = '';
        
        if ( $spouses ) {
            $spouse_html .= '<tr>';
            $spouse_html .= '<td colspan="2"></td>';
            $spouse_html .= '</tr>';
            $sp = 1;
            foreach ( $spouses as $key => $spouse ) {
                $spouse_html .= '<tr>';
                $spouse_html .= '<td valign="top">';
                $spouse_html .= 'Spouse ';
                $spouse_html .= '</td>';
                $spouse_html .= '<td style="padding:0px;border:0;">';
                $spouse_html .= '<table style="margin:0px;border:0;">';
                $spouse_html .= '<tr>';
                $spouse_html .= '<td  width="50" valign="top">';
                $spouse_html .= '#' . $sp;
                $sp++;
                $spouse_html .= '</td>';
                $spouse_html .= '<td  style="padding:0px;border:0;">';
                $spouse_html .= '<table style="margin:0px;border:0;">';
                $spouse_html .= '<tr>';
                $spouse_html .= '<td  width="200">';
                $spouse_html .= 'Name';
                $spouse_html .= '</td>';
                $spouse_html .= '<td>';
                
                if ( $spouse['id'] ) {
                    $spouse_html .= '<a href="' . get_the_permalink( $spouse['id'] ) . '">' . get_post_meta( $spouse['id'], 'full_name', true ) . '</a>';
                } else {
                    $spouse_html .= 'Unknown';
                }
                
                $spouse_html .= '</td>';
                $spouse_html .= '</tr>';
                /*
                $spouse_html .= '<tr>';
                	$spouse_html .= '<td style="border-left:0px;">';
                		$spouse_html .= 'Date of Marriage';
                	$spouse_html .= '</td>';
                	$spouse_html .= '<td   style="border-right:0px;">';
                	$spouse_html .= '</td>';
                $spouse_html .= '</tr>';
                
                $spouse_html .= '<tr>';
                	$spouse_html .= '<td style="border-left:0px;">';
                		$spouse_html .= 'Place of Marriage';
                	$spouse_html .= '</td>';
                	$spouse_html .= '<td   style="border-right:0px;">';
                	$spouse_html .= '</td>';
                $spouse_html .= '</tr>';
                */
                $childrens = $this->get_childrens( $post_id, $spouse['id'] );
                
                if ( $childrens ) {
                    $spouse_html .= '<tr>';
                    $spouse_html .= '<td valign="top">';
                    $spouse_html .= 'Children';
                    $spouse_html .= '</td>';
                    $spouse_html .= '<td>';
                    $children_html = array();
                    if ( $childrens ) {
                        foreach ( $childrens as $key => $children ) {
                            array_push( $children_html, '<a href="' . get_the_permalink( $children ) . '">' . get_post_meta( $children, 'full_name', true ) . '</a>' );
                        }
                    }
                    $spouse_html .= implode( ', ', $children_html );
                    $spouse_html .= '</td>';
                    $spouse_html .= '</tr>';
                }
                
                $spouse_html .= '</table>';
                $spouse_html .= '</td>';
                $spouse_html .= '</tr>';
                $spouse_html .= '</table>';
                $spouse_html .= '</td>';
                $spouse_html .= '</tr>';
            }
        }
        
        $siblings = $this->get_siblings( $post_id );
        $sibling_html = array();
        foreach ( $siblings as $key => $sibling ) {
            if ( $sibling !== $post_id ) {
                array_push( $sibling_html, '<a href="' . get_the_permalink( $sibling ) . '">' . get_post_meta( $sibling, 'full_name', true ) . '</a>' );
            }
        }
        
        if ( $sibling_html ) {
            $sibling_html = '
			<tr>
			<td colspan="2"></td>
			</tr>
			<tr>
			<td>Siblings</td>
			<td>
			' . implode( ', ', $sibling_html ) . '
			</td>
			</tr>';
        } else {
            $sibling_html = '';
        }
        
        $birt_html = '';
        if ( isset( $event['birt'] ) && $event['birt'] ) {
            foreach ( $event['birt'] as $key => $birt ) {
                if ( !isset( $birt['place'] ) ) {
                    $birt['place'] = '';
                }
                if ( !$birt['date'] && !$birt['place'] ) {
                    unset( $event['birt'][$key] );
                }
            }
        }
        
        if ( isset( $event['birt'] ) && $event['birt'] ) {
            $birt_html .= '<tr>';
            $birt_html .= '<td colspan="2"></td>';
            $birt_html .= '</tr>';
            $birt_html .= '<tr>';
            $birt_html .= '<td valign="top">';
            $birt_html .= 'Birth';
            $birt_html .= '</td>';
            $birt_html .= '<td style="padding:0;border:0;">';
            $birt_html .= '<table style="margin:0; border:0;">';
            $ref = 1;
            foreach ( $event['birt'] as $key => $birt ) {
                
                if ( isset( $birt['date'] ) || isset( $birt['place'] ) ) {
                    $birt_html .= '<tr>';
                    $birt_html .= '<td valign="top" width="50">';
                    $birt_html .= '#' . $ref;
                    $ref++;
                    $birt_html .= '</td>';
                    $birt_html .= '<td style="padding:0px;border:0;">';
                    $birt_html .= '<table style="margin:0px;border:0;">';
                    
                    if ( isset( $birt['date'] ) && $birt['date'] ) {
                        $birt_html .= '<tr>';
                        $birt_html .= '<td width="200">';
                        $birt_html .= 'Date of Birth';
                        $birt_html .= '</td>';
                        $birt_html .= '<td>';
                        $birt_html .= $birt['date'];
                        $birt_html .= '</td>';
                        $birt_html .= '</tr>';
                    }
                    
                    
                    if ( isset( $birt['place'] ) && $birt['place'] ) {
                        $birt_html .= '<tr>';
                        $birt_html .= '<td width="200">';
                        $birt_html .= 'Place of Birth';
                        $birt_html .= '</td>';
                        $birt_html .= '<td>';
                        $birt_html .= $birt['place'];
                        $birt_html .= '</td>';
                        $birt_html .= '</tr>';
                    }
                    
                    $birt_html .= '</table>';
                    $birt_html .= '</td>';
                    $birt_html .= '</tr>';
                }
            
            }
            $birt_html .= '</table>';
            $birt_html .= '</td>';
            $birt_html .= '</tr>';
        }
        
        $deat_html = '';
        if ( isset( $event['deat'] ) && $event['deat'] ) {
            foreach ( $event['deat'] as $key => $deat ) {
                if ( !isset( $deat['place'] ) ) {
                    $deat['place'] = '';
                }
                if ( !$deat['date'] && !$deat['place'] ) {
                    unset( $event['deat'][$key] );
                }
            }
        }
        
        if ( isset( $event['deat'] ) && $event['deat'] ) {
            $deat_html .= '<tr>';
            $deat_html .= '<td colspan="2"></td>';
            $deat_html .= '</tr>';
            $deat_html .= '<tr>';
            $deat_html .= '<td valign="top">';
            $deat_html .= 'Death';
            $deat_html .= '</td>';
            $deat_html .= '<td style="padding:0;border:0;">';
            $deat_html .= '<table style="margin:0; border:0;">';
            $ref = 1;
            foreach ( $event['deat'] as $key => $deat ) {
                
                if ( isset( $deat['date'] ) || isset( $deat['place'] ) ) {
                    $deat_html .= '<tr>';
                    $deat_html .= '<td valign="top" width="50">';
                    $deat_html .= '#' . $ref;
                    $ref++;
                    $deat_html .= '</td>';
                    $deat_html .= '<td style="padding:0px;border:0;">';
                    $deat_html .= '<table style="margin:0px;border:0;">';
                    
                    if ( isset( $deat['date'] ) && $deat['date'] ) {
                        $deat_html .= '<tr>';
                        $deat_html .= '<td width="200">';
                        $deat_html .= 'Date of Death';
                        $deat_html .= '</td>';
                        $deat_html .= '<td>';
                        $deat_html .= $deat['date'];
                        $deat_html .= '</td>';
                        $deat_html .= '</tr>';
                    }
                    
                    
                    if ( isset( $deat['place'] ) && $deat['place'] ) {
                        $deat_html .= '<tr>';
                        $deat_html .= '<td width="200">';
                        $deat_html .= 'Place of Death';
                        $deat_html .= '</td>';
                        $deat_html .= '<td>';
                        $deat_html .= $deat['place'];
                        $deat_html .= '</td>';
                        $deat_html .= '</tr>';
                    }
                    
                    $deat_html .= '</table>';
                    $deat_html .= '</td>';
                    $deat_html .= '</tr>';
                }
            
            }
            $deat_html .= '</table>';
            $deat_html .= '</td>';
            $deat_html .= '</tr>';
        }
        
        $address_html = '';
        
        if ( isset( $event['address_(other)'] ) ) {
            $address_html .= '<tr>';
            $address_html .= '<td colspan="2"></td>';
            $address_html .= '</tr>';
            $address = array();
            foreach ( $event['address_(other)'] as $keya => $value ) {
                array_push( $address, $value );
            }
            $address_html .= '<tr>';
            $address_html .= '<td valign="top">';
            $address_html .= 'Location';
            $address_html .= '</td>';
            $address_html .= '<td style="padding:0px;border:0;">';
            $address_html .= '<table style="margin:0px;border:0;">';
            $ref = 1;
            foreach ( $address as $keyas => $address_single ) {
                
                if ( isset( $address_single['place'] ) && $address_single['place'] ) {
                    $address_html .= '<tr>';
                    $address_html .= '<td width="50">';
                    $address_html .= '#' . $ref;
                    $ref++;
                    $address_html .= '</td>';
                    $address_html .= '<td>';
                    $address_html .= $address_single['place'];
                    if ( isset( $address_single['date'] ) && $address_single['date'] ) {
                        $address_html .= ' (' . $address_single['date'] . ') ';
                    }
                    $address_html .= '</td>';
                    $address_html .= '</tr>';
                }
            
            }
            $address_html .= '</table>';
            $address_html .= '</td>';
            $address_html .= '</tr>';
        }
        
        $aditionals_events = array(
            'buri'            => array(
            'type'  => 'buri',
            'title' => __( 'Burial', 'genealogical-tree' ),
        ),
            'adop'            => array(
            'type'  => 'adop',
            'title' => __( 'Adoption', 'genealogical-tree' ),
        ),
            'enga'            => array(
            'type'  => 'enga',
            'title' => __( 'Engagement', 'genealogical-tree' ),
        ),
            'marr'            => array(
            'type'  => 'marr',
            'title' => __( 'Marriage', 'genealogical-tree' ),
        ),
            'div'             => array(
            'type'  => 'div',
            'title' => __( 'Divorce', 'genealogical-tree' ),
        ),
            'address_(other)' => array(
            'type'  => 'address_(other)',
            'title' => __( 'Address (Other)', 'genealogical-tree' ),
        ),
            'bapm'            => array(
            'type'  => 'bapm',
            'title' => __( 'Baptism', 'genealogical-tree' ),
        ),
            'arms'            => array(
            'type'  => 'arms',
            'title' => __( 'arms', 'genealogical-tree' ),
        ),
            'occupation_1'    => array(
            'type'  => 'occupation_1',
            'title' => __( 'Occupation', 'genealogical-tree' ),
        ),
        );
        if ( isset( $event['birt'] ) ) {
            unset( $event['birt'] );
        }
        if ( isset( $event['deat'] ) ) {
            unset( $event['deat'] );
        }
        if ( isset( $event['address_(other)'] ) ) {
            unset( $event['address_(other)'] );
        }
        $events_html = '';
        
        if ( $event ) {
            $events_html .= '<tr>';
            $events_html .= '<td colspan="2"></td>';
            $events_html .= '</tr>';
            $events_html .= '<tr>';
            $events_html .= '<td valign="top" colspan="2">';
            $events_html .= 'Events ';
            $events_html .= '</td>';
            $events_html .= '</tr>';
            $events_html .= '<tr>';
            $events_html .= '<td  colspan="2" style="padding:0;border:0;">';
            $events_html .= '<table style="margin:0; border:0;">';
            foreach ( $event as $key => $ev ) {
                
                if ( $key != 'birt' && $key != 'deat' && $key != 'address_(other)' ) {
                    $ref = 1;
                    foreach ( $ev as $keyx => $evs ) {
                        $events_html .= '<tr>';
                        
                        if ( $ref == 1 ) {
                            $events_html .= '<td rowspan="' . count( $ev ) . '" width="200"  valign="top">';
                            
                            if ( isset( $aditionals_events[$key] ) ) {
                                $events_html .= $aditionals_events[$key]['title'];
                            } else {
                                $events_html .= ucfirst( str_replace( '_', ' ', $key ) );
                            }
                            
                            $events_html .= '</td>';
                        }
                        
                        $events_html .= '<td width="50"  valign="top">';
                        $events_html .= ' #' . $ref;
                        $ref++;
                        $events_html .= '</td>';
                        $events_html .= '<td style="padding:0;border:0;">';
                        $events_html .= '<table style="margin:0; border:0;">';
                        
                        if ( isset( $evs['date'] ) && $evs['date'] ) {
                            $events_html .= '<tr>';
                            $events_html .= '<td width="200" valign="top">';
                            $events_html .= 'Date';
                            $events_html .= '</td>';
                            $events_html .= '<td>';
                            $events_html .= $evs['date'];
                            $events_html .= '</td>';
                            $events_html .= '</tr>';
                        }
                        
                        
                        if ( isset( $evs['place'] ) && $evs['place'] ) {
                            $events_html .= '<tr>';
                            $events_html .= '<td width="200">';
                            $events_html .= 'Place';
                            $events_html .= '</td>';
                            $events_html .= '<td>';
                            $events_html .= $evs['place'];
                            $events_html .= '</td>';
                            $events_html .= '</tr>';
                        }
                        
                        $events_html .= '</table>';
                        $events_html .= '</td>';
                        $events_html .= '</tr>';
                    }
                }
            
            }
            $events_html .= '</table>';
            $events_html .= '</td>';
            $events_html .= '</tr>';
        }
        
        $html .= '
		<table class="table table-hover table-condensed indi">
			<tbody>
				<tr>
					<td width="200">Full Name</td>
					<td><a href="' . get_the_permalink( $post_id ) . '">' . $full_name . '</a> - ' . $tree_link . '</td>
				</tr>

				' . $birt_html . '';
        if ( $father_id || $mother_id ) {
            $html .= '
					<tr>
						<td colspan="2"></td>
					</tr>';
        }
        if ( $father_id ) {
            $html .= '
					<tr>
						<td>Father</td>
						<td><a href="' . get_the_permalink( $father_id ) . '">' . $father . '</a></td>
					</tr>';
        }
        if ( $mother_id ) {
            $html .= '
					<tr>
						<td>Mother</td>
						<td><a href="' . get_the_permalink( $mother_id ) . '">' . $mother . '</a></td>
					</tr>';
        }
        $html .= '
				' . $sibling_html . '
				' . $spouse_html . '
				' . $deat_html . '
				' . $address_html . '
				' . $events_html . '

			</tbody>
		</table>
		';
        return $html;
    }
    
    /**
     * Filter callback for displaing data into single page
     *
     * @since    1.0.0
     */
    public function data_in_single_page( $content )
    {
        $html = '';
        
        if ( is_singular( 'gt-member' ) ) {
            global  $post ;
            $post_id = $post->ID;
            $html = $this->single_member_info( $post_id );
        }
        
        return $html . $content;
    }
    
    /**
     * Get root by family group 
     *
     * @since    1.0.0
     */
    public function get_root_by_family_group( $family_group )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'tax_query'      => array( array(
            'taxonomy' => 'gt-family-group',
            'field'    => 'term_id',
            'terms'    => $family_group,
        ) ),
        ) );
        // find who dont have father and mother.
        $possibles = array();
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $father = get_post_meta( $member->ID, 'father', true );
                $mother = get_post_meta( $member->ID, 'mother', true );
                if ( !$father && !$mother ) {
                    array_push( $possibles, $member->ID );
                }
            }
        }
        // find whome spouses have father and mother remove theme.
        $getdual = array();
        foreach ( $possibles as $key => $possible ) {
            $spouses = get_post_meta( $possible, 'spouses', true );
            if ( $spouses ) {
                foreach ( $spouses as $keysp => $spouse ) {
                    if ( in_array( $spouse['id'], $possibles ) ) {
                        array_push( $getdual, array( $possible, $spouse['id'] ) );
                    }
                    $father = get_post_meta( $spouse['id'], 'father', true );
                    $mother = get_post_meta( $spouse['id'], 'mother', true );
                    if ( $mother || $father ) {
                        unset( $possibles[$key] );
                    }
                }
            }
        }
        $getdualNew = array();
        foreach ( $getdual as $key => $value ) {
            $getdualNew[$value[0] + $value[1]] = $value;
            if ( ($keyc = array_search( $value[0], $possibles )) !== false ) {
                unset( $possibles[$keyc] );
            }
            if ( ($keyd = array_search( $value[1], $possibles )) !== false ) {
                unset( $possibles[$keyd] );
            }
        }
        $finalArray = array();
        foreach ( $possibles as $key => $possible ) {
            $possibleSex = get_post_meta( $possible, 'sex', true );
            $husb = '';
            $wife = '';
            if ( $possibleSex === 'M' ) {
                $husb = $possible;
            }
            if ( $possibleSex === 'F' ) {
                $wife = $possible;
            }
            if ( !$possibleSex ) {
                $husb = $possible;
            }
            if ( $husb || $wife ) {
                array_push( $finalArray, array(
                    'husb' => $husb,
                    'wife' => $wife,
                ) );
            }
        }
        $getdualNews = array();
        foreach ( $getdualNew as $key => $getd ) {
            foreach ( $getd as $keby => $valu ) {
                if ( get_post_meta( $valu, 'spouses', true ) ) {
                    if ( count( get_post_meta( $valu, 'spouses', true ) ) > 1 ) {
                        array_push( $getdualNews, $getd );
                    }
                }
            }
        }
        return array(
            'single' => $finalArray,
            'dual'   => $getdualNews,
        );
    }
    
    /**
     * Get display tree for shortcode
     *
     * @since    1.0.0
     */
    public function display_tree( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'root'   => null,
            'family' => null,
            'style'  => 1,
        ), $atts );
        if ( !$data['family'] ) {
            return 'No data';
        }
        
        if ( $data['family'] ) {
            $tree = $this->get_root_by_family_group( $data['family'] );
            $tree = current( current( current( $tree ) ) );
        }
        
        if ( isset( $_GET['tree'] ) ) {
            $data['root'] = $_GET['tree'];
        }
        if ( $data['root'] ) {
            $tree = $data['root'];
        }
        if ( !$tree ) {
            return 'No data';
        }
        if ( $data['style'] == '1' ) {
            return $this->display_tree_style1( $tree );
        }
    }
    
    /**
     * Get display members for shortcode
     *
     * @since    1.0.0
     */
    public function display_tree_list( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'family' => null,
        ), $atts );
        if ( !$data['family'] ) {
            return 'No data';
        }
        $family_group_id = $data['family'];
        // Find unique family in to group
        $tree_root = $this->get_root_by_family_group( $family_group_id );
        $post_id = get_term_meta( $family_group_id, 'tree_page', true );
        $ph = '';
        $ph .= '<div class="gt-tree-list-pub">';
        if ( $tree_root['single'] ) {
            foreach ( $tree_root['single'] as $key => $value ) {
                if ( $value['husb'] ) {
                    $ph .= '<div><a href="' . get_the_permalink( $post_id ) . '?tree=' . $value['husb'] . '">' . get_the_title( $value['husb'] ) . '</a></div>';
                }
                if ( $value['wife'] ) {
                    $ph .= '<div><a href="' . get_the_permalink( $post_id ) . '?tree=' . $value['wife'] . '">' . get_the_title( $value['wife'] ) . '</a></div>';
                }
            }
        }
        if ( $tree_root['dual'] ) {
            foreach ( $tree_root['dual'] as $key => $value ) {
                $ph .= '<div><a href="' . get_the_permalink( $post_id ) . '?tree=' . $value[0] . '">' . get_the_title( $value[0] ) . ' and ' . get_the_title( $value[1] ) . '</a></div>';
            }
        }
        $ph .= '</div>';
        return $ph;
    }
    
    /**
     * Get display members for shortcode
     *
     * @since    1.0.0
     */
    public function display_members( $atts, $content = null )
    {
        $html = '';
        $html = '<div class="gt-members-pub">';
        $data = shortcode_atts( array(
            'family' => null,
            'ids'    => null,
        ), $atts );
        
        if ( $data['family'] ) {
            $members = $this->get_all_members_of_family( $data['family'] );
            $html .= '<ul>';
            foreach ( $members as $key => $member ) {
                $html .= '<li>';
                $html .= '<h3> # ' . get_the_title( $member ) . '</h3>';
                $html .= $this->single_member_info( $member );
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        
        
        if ( $data['ids'] ) {
            $members = explode( ',', $data['ids'] );
            $html .= '<ul>';
            foreach ( $members as $key => $member ) {
                $html .= '<li>';
                $html .= $this->single_member_info( $member );
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Get all members of family by group ID
     *
     * @since    1.0.0
     */
    public function get_all_members_of_family( $family_group )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array( array(
            'taxonomy' => 'gt-family-group',
            'field'    => 'term_id',
            'terms'    => $family_group,
        ) ),
        ) );
        return $query->posts;
    }
    
    /**
     * Display tree style1
     *
     * @since    1.0.0
     */
    public function display_tree_style1( $tree )
    {
        return '
		<div id="genealogical-tree-cont" class="genealogical-tree-cont">
			<div id="spinner" class="spinner" style="display:none;">
				<img id="img-spinner" src="' . plugin_dir_url( __FILE__ ) . '/img/spinner.gif" alt="Loading">
			</div>
			<div id="content" class="gt-content" style="border: 1px solid #d5d5d5; overflow: hidden;">
				<div id="famTree" class="tree tree-style-1"></div>
				<script>
					jQuery(window).on(\'load\', function(){
						jQuery( document ).ready(
							ginge.loadIndi("' . $tree . '", "' . $tree . '", false)
						);
					})
				</script>
			</div>
		</div>';
    }
    
    /**
     * Get fam by root id
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
     *  Tree by id
     *
     * @since    1.0.0
     */
    public function hard_tree( $tree )
    {
        $root['ind'] = $tree;
        
        if ( $this->get_fam_ref_id( $tree ) ) {
            $y = 0;
            foreach ( $this->get_fam_ref_id( $tree ) as $keyf => $fam ) {
                $root['fam'][$y]['spouse'] = get_post_meta( $fam, 'spouse', true );
                $root['fam'][$y]['chill'] = get_post_meta( $fam, 'chill', true );
                
                if ( $root['fam'][$y]['chill'] ) {
                    $x = 0;
                    foreach ( $root['fam'][$y]['chill'] as $keyc => $chill ) {
                        $root['fam'][$y]['chill'][$x] = $this->hard_tree( $chill );
                        $x++;
                    }
                }
                
                $y++;
            }
        }
        
        return $root;
    }

}