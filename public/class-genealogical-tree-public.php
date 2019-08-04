<?php

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
class Genealogical_Tree_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-familytree', plugin_dir_url( __FILE__ ) . 'css/familytree.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'tree-json', plugin_dir_url( __FILE__ ) . 'css/tree-json.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( 'underscore');
		wp_enqueue_script( $this->plugin_name.'-tooltip', plugin_dir_url( __FILE__ ) . 'js/tooltip.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-popper', plugin_dir_url( __FILE__ ) . 'js/popper.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-json', plugin_dir_url( __FILE__ ) . 'js/json.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-familytree', plugin_dir_url( __FILE__ ) . 'js/familytree.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-panzoom',  plugin_dir_url( __FILE__ ) . 'js/panzoom.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-public.js', array( 'jquery' ), $this->version, true );
		

	}

	/**
	 * Register get siblings by ID
	 *
	 * @since    1.0.0
	 */
	public function get_siblings($post_id) {
		$siblings = array();
		$father = get_post_meta($post_id, 'father', true);
		$mother = get_post_meta($post_id, 'mother', true);
		$query = new WP_Query(array(
			'post_type' => 'family',
			'posts_per_page' => 1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'root',
					'value' => $father,
					'compare' => '=',
				),
				array(
					'key' => 'spouse',
					'value' => $mother,
					'compare' => '=',
				),
			)
		));
		if($query->posts) {
			$family = current($query->posts);
			$siblings = get_post_meta($family->ID, 'chill', true);
		}
		return $siblings;
	}

	/**
	 * Register get get childrens by father ID and mother ID
	 *
	 * @since    1.0.0
	 */
	public function get_childrens($root, $spouse) {
		$chill = array();
		$query = new WP_Query(array(
			'post_type' => 'family',
			'posts_per_page' => 1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'root',
					'value' => $root,
					'compare' => '=',
				),
				array(
					'key' => 'spouse',
					'value' => $spouse,
					'compare' => '=',
				),
			)
		));
		if($query->posts) {
			$family = current($query->posts);
			$chill = get_post_meta($family->ID, 'chill', true);
		}
		return $chill;
	}
	
	/**
	 * filter callback for displaing data into single page
	 *
	 * @since    1.0.0
	 */
	public function single_member_info($post_id) {

		$html = '';
		if(!$post_id) {
			return $html;
		}
		
		if(!get_post($post_id)){
			return $html;
		}
		
		$full_name = get_post_meta($post_id, 'full_name', true);
		$event = get_post_meta($post_id, 'event', true);
		$mother_id = get_post_meta($post_id, 'mother', true);
		$father_id = get_post_meta($post_id, 'father', true);

		$mother = get_post_meta($mother_id, 'full_name', true);
		$father = get_post_meta($father_id, 'full_name', true);
		$spouses = get_post_meta($post_id, 'spouses', true);
	
		$spouse_html = '';
		if($spouses){
			foreach ($spouses as $key => $spouse) {
				$spouse_html .= '<tr>';
					$spouse_html .= '<td valign="top">';
						$spouse_html .= 'Spouse #1';
					$spouse_html .= '</td>';
					$spouse_html .= '<td style="padding:0px; ">';
						$spouse_html .= '<table style="margin:0px;">';
							$spouse_html .= '<tr>';
								$spouse_html .= '<td  width="200"  style="border-left:0px;border-top:0px;">';
									$spouse_html .= 'Name';
								$spouse_html .= '</td>';
								$spouse_html .= '<td   style="border-right:0px;border-top:0px;">';
									$spouse_html .= '<a href="'.get_the_permalink($spouse['id']).'">'.get_post_meta($spouse['id'], 'full_name', true).'</a>';
								$spouse_html .= '</td>';
							$spouse_html .= '</tr>';
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
							$spouse_html .= '<tr>';
								$spouse_html .= '<td valign="top"  style="border-left:0px;border-bottom:0px;">';
									$spouse_html .= 'Children';
								$spouse_html .= '</td>';
								$spouse_html .= '<td   style="border-right:0px;border-bottom:0px;">';
									$childrens = $this->get_childrens($post_id, $spouse['id']);
									$children_html = array();	
									if($childrens){
										foreach ($childrens as $key => $children) {
											array_push($children_html,'<a href="'.get_the_permalink($children).'">'.get_post_meta($children, 'full_name', true).'</a>');
										}
									}
									$spouse_html .= implode(', ', $children_html);
								$spouse_html .= '</td>';
							$spouse_html .= '</tr>';
						$spouse_html .= '</table>';
					$spouse_html .= '</td>';
				$spouse_html .= '</tr>';
			}
		}

		$siblings = $this->get_siblings($post_id);
		$sibling_html = array();
		foreach ($siblings as $key => $sibling) {
			if($sibling!==$post_id){
				array_push($sibling_html, '<a href="'.get_the_permalink($sibling).'">'.get_post_meta($sibling, 'full_name', true).'</a>');
			}
		}

		$dob = null;
		$pob = null;
		if( isset( $event['birt'] ) ) {
			$birt = array();
			foreach ($event['birt'] as $keyb => $value) {
				array_push($birt, $value);
			}
			if( isset( $birt[0] ) ) {
				$dob = $birt[0]['date'];
				$pob = isset($birt[0]['place']) ? $birt[0]['place'] : '';
			}
		}

		$dod = null;
		$pod = null;
		if( isset( $event['deat'] ) ) {
			$deat = array();
			foreach ($event['deat'] as $keyd => $value) {
				array_push($deat, $value);
			}
			if( isset( $deat[0] ) ) {
				$dod = $deat[0]['date'];
				$pod = isset($deat[0]['place']) ? $deat[0]['place'] : '' ;
			}
		}

		$address_html = '';

		if( isset( $event['address_(other)'] ) ) {
			$address = array();
			foreach ($event['address_(other)'] as $keya => $value) {
				array_push($address, $value);
			}

			
			$address_html .= '<tr>';
				$address_html .= '<td valign="top">';
					$address_html .= 'Location';
				$address_html .= '</td>';
				$address_html .= '<td style="padding:0px;">';
					$address_html .= '<table style="margin:0px;">';

			foreach ($address as $keyas => $address_single) {
				$bt0='';
				$bb0='';
				if($keyas===0){
					$bt0 = 'border-top:0px;';
				}

				if((count($address)-1)===$keyas){
					$bb0 = 'border-bottom:0px;';
				}

				$address_html .= '<tr>';
					$address_html .= '<td style="border-right:0px;border-left:0px;'.$bt0.''.$bb0.'">';
					$address_html .= $address_single['place'];
					$address_html .= ' ('.$address_single['date'].') ';
					$address_html .= '</td>';
				$address_html .= '</tr>';
				
			}
					$address_html .= '</table>';
				$address_html .= '</td>';
			$address_html .= '</tr>';
		}

		$html .= '
		<table class="table table-hover table-condensed indi">
			<tbody>
				<tr>
					<td width="200">Full Name</td>
					<td>'.$full_name.'</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>Date of Birth</td>
					<td>'.$dob.'</td>
				</tr>
				<tr>
					<td>Place of Birth</td>
					<td>
						'.$pob.'
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>Father</td>
					<td><a href="'.get_the_permalink($father_id).'">'.$father.'</a></td>
				</tr>
				<tr>
					<td>Mother</td>
					<td><a href="'.get_the_permalink($mother_id).'">'.$mother.'</a></td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>Siblings</td>
					<td>
						'.implode(', ', $sibling_html).'
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				'.$spouse_html.'
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>Date of Death</td>
					<td>'.$dod.'</td>
				</tr>
				<tr>
					<td>Place of Death</td>
					<td>
						'.$pod.'
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				'.$address_html.'
			</tbody>
		</table>
		';
		return $html;
	}
	/**
	 * filter callback for displaing data into single page
	 *
	 * @since    1.0.0
	 */
	public function data_in_single_page($content) {
		$html = '';
		if( is_singular( 'member' ) ) {
			global $post;
			$post_id = $post->ID;
			$html = $this->single_member_info($post_id);
		}
		return $html.$content;
	}

	/**
	 * Get root by family group 
	 *
	 * @since    1.0.0
	 */
	public function get_root_by_family_group($family_group){
		$query = new WP_Query( array(
			'post_type' => 'member',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'family_group',
					'field'    => 'term_id',
					'terms'    => $family_group,
				),
			),
		));

		// find who dont have father and mother.
		$possibles = array();
		if($query->posts){
			foreach ($query->posts as $key => $member) {
				$father = get_post_meta($member->ID, 'father', true);
				
				$mother = get_post_meta($member->ID, 'mother', true);
				if(!$father && !$mother) {
					array_push($possibles, $member->ID);
				}
			}
		}

		// find whome spouses have father and mother remove theme.
		$getdual = array();
		foreach ($possibles as $key => $possible) {
			$spouses = get_post_meta($possible, 'spouses', true);
			if($spouses) {
				foreach ($spouses as $keysp => $spouse) {
					if(in_array($spouse['id'], $possibles)){
						array_push($getdual, array($possible, $spouse['id']));
					}
					$father = get_post_meta($spouse['id'], 'father', true);
					$mother = get_post_meta($spouse['id'], 'mother', true);
					if($mother || $father) {
						unset($possibles[$key]);
					}
				}
			}
		}

		$getdualNew = array();
		foreach ($getdual as $key => $value) {
			$getdualNew[$value[0]+$value[1]] = $value;
			if (($keyc = array_search($value[0], $possibles)) !== false) {
				unset($possibles[$keyc]);
			}
			if (($keyd = array_search($value[1], $possibles)) !== false) {
				unset($possibles[$keyd]);
			}
		}
		
		$finalArray = array();
		foreach ($possibles as $key => $possible) {
			$possibleSex = get_post_meta($possible, 'sex', true);
			$husb = '';
			$wife = '';
			if($possibleSex==='M') {
				$husb = $possible;
			}
			if($possibleSex==='F') {
				$wife = $possible;
			}

			if(!$possibleSex){
				$husb = $possible;
			}

			if($husb || $wife) {
				array_push( $finalArray, array (
					'husb' => $husb, 
					'wife' => $wife
				) );
			}
		}

		$getdualNews = array();

		foreach ($getdualNew as $key => $getd) {
			foreach ($getd as $keby => $valu) {
				if(get_post_meta($valu, 'spouses', true)){
					if(count(get_post_meta($valu, 'spouses', true)) > 1) {
						array_push($getdualNews, $getd);
					}
				}
			}
		}
		return array(
			'single' => $finalArray,
			'dual' => $getdualNews
		);
	}


	/**
	 * Get display tree for shortcode
	 *
	 * @since    1.0.0
	 */
	public function display_tree($atts, $content = null ) {
		$data = shortcode_atts( array(
			'root' => null,
			'family' => null,
			'style' => 1,
		), $atts );

		if(isset($_GET['tree'])){
			$data['root'] = $_GET['tree'];
		}


		if($data['family']){
			$tree = $this->get_root_by_family_group($data['family']);
			$tree = current(current(current($tree)));
		}

		if($data['root']){
			$tree = $data['root'];
		}

		if($data['style']=='json'){
			$this->display_tree_json($tree);
		}


		if($data['style']=='1'){
			return $this->display_tree_style1($tree);
		}

		//if($data['style']=='2'){
			//$this->display_tree_style2($tree);
		//}

		//if($data['style']=='3'){
			//$this->display_tree_style3($tree);
		//}

	}

	/**
	 * Get display members for shortcode
	 *
	 * @since    1.0.0
	 */
	public function display_members($atts, $content = null ) {
		$html = '';
		$data = shortcode_atts( array(
			'family' => null,
			'ids' => null,
		), $atts );

		if($data['family']){
			$members = $this->get_all_members_of_family($data['family']);
			$html .= '<ol>';
			foreach ($members as $key => $member) {
				$html .= '<li>';
				$html .= $this->single_member_info($member);
				$html .= '</li>';
			}
			$html .= '</ol>';
		}

		if($data['ids']){
			$members = explode(',', $data['ids']);
			$html .= '<ol>';
			foreach ($members as $key => $member) {
				$html .= '<li>';
				$html .= $this->single_member_info($member);
				$html .= '</li>';
			}
			$html .= '</ol>';
		}

		return $html;
	}

	/**
	 * Get root by family group 
	 *
	 * @since    1.0.0
	 */
	public function get_all_members_of_family($family_group){
		$query = new WP_Query( array(
			'post_type' => 'member',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'tax_query' => array(
				array(
					'taxonomy' => 'family_group',
					'field'    => 'term_id',
					'terms'    => $family_group,
				),
			),
		));

		return $query->posts;
	}



	public function display_tree_json($tree){
		$tree = $this->hard_tree($tree);
		print_r('<pre>');
		//print_r($tree);
		print_r('</pre>');

	}

	public function display_tree_style1($tree){
		return '
		<div id="genealogical-tree-cont" class="genealogical-tree-cont">
			<div id="spinner" class="spinner" style="display:none;">
				<img id="img-spinner" src="'.plugin_dir_url( __FILE__ ).'/img/spinner.gif" alt="Loading">
			</div>
			<div id="content" class="gt-content" style="border: 1px solid #d5d5d5; overflow: hidden;">
				<div id="famTree" class="tree tree-style-1"></div>
				<script>
					jQuery(window).on(\'load\', function(){
						jQuery( document ).ready(
							ginge.loadIndi("'.$tree.'", "'.$tree.'", false)
						);
					})
				</script>
			</div>
		</div>';

	}

	public function display_tree_style2($tree){
		$tree = $this->hard_tree($tree);
		$html = '';
		$html .= '<ul class="tree tree-style-2">';
			$html .= '<li>';
				$html .= $this->family_style2($tree);
			$html .= '</li>';
		$html .= '</ul>';
		echo $html;
	}

	public function display_tree_style3($tree){
		$tree = $this->hard_tree($tree);
		$html = '';
		$html .= '<ul class="tree tree-style-3">';
			$html .= '<li> <b>Family Root: </b>';
				$html .= $this->family_style3($tree);
			$html .= '</li>';
		$html .= '</ul>';
		echo $html;
	}

	public function family_style2($tree){

		$html = '<div class="indi">';
		$html .= '<a href="'.get_the_permalink($tree['ind']).'">';
		$html .= get_the_title($tree['ind']);
		$html .= '</a>';
		$html .= '</div>';
		if($tree['fam']){
			$mt_rand = mt_rand();
			$html .= ' <label for="'.$mt_rand.$tree['ind'].'"> [+] </label> <input type="checkbox" id="'.$mt_rand.$tree['ind'].'">';
			$html .= '<ul class="fams">';
			foreach ($tree['fam'] as $key => $fam) {
				$rand = md5(uniqid(rand(), true));
				$html .= '<li class="fam">';
					if(!$fam['spouse']){
						$html .= '<div class="indi">';
						$html .= '<a href="#">';
						$html .= 'Unknown';
						$html .= '</a>';
						$html .= '</div>';
						if($fam['chill']){
							$mt_rand = mt_rand();
							$html .= ' <label for="un-'.$mt_rand.$rand.'"> [+] </label> <input type="checkbox" id="un-'.$mt_rand.$rand.'">';
						}
					} else {
						$html .= '<div class="indi">';
						$html .= '<a href="'.get_the_permalink($fam['spouse']).'">';
						$html .= get_the_title($fam['spouse']);
						$html .= '</a>';
						$html .= '</div>';
						if($fam['chill']){
							$mt_rand = mt_rand();
							$html .= ' <label for="'.$mt_rand.$fam['spouse'].'"> [+] </label> <input type="checkbox" id="'.$mt_rand.$fam['spouse'].'">';
						}
					}
					if($fam['chill']){	
						$html .= '<ul class="chills">';
						foreach ($fam['chill'] as $key => $chill) {
							$html .= '<li class="chill">';
							$html .= $this->family_style2($chill);
							$html .= '</li>';
						}
						$html .= '</ul>';
					}
				$html .= '</li>';
			}
		
		$html .= '</ul>';
		}
		return $html;
	}

	public function family_style3($tree){
		$html = '<div class="indi">';
		$html .= '<a href="'.get_the_permalink($tree['ind']).'">';
		$html .= get_the_title($tree['ind']);
		$html .= '</a> ';
		$html .= '</div>';
		$html .= '<div class="links">';
		$html .= ' <a href=""><span class="dashicons dashicons-admin-links"></span></a> <a href=""><span class="dashicons dashicons-networking"></span></a>';
		$html .= '</div>';
		if($tree['fam']){
			$mt_rand = mt_rand();
			$html .= ' <label for="'.$mt_rand.$tree['ind'].'"> [+] </label> <input type="checkbox" id="'.$mt_rand.$tree['ind'].'">';
			$html .= '<ul class="fams">';
			foreach ($tree['fam'] as $key => $fam) {
				$rand = md5(uniqid(rand(), true));
				$html .= '<li class="fam"><b>Spouse '.($key+1).': </b>';
					if(!$fam['spouse']){
						$html .= '<div class="indi">';
						$html .= '<a href="#">';
						$html .= 'Unknown';
						$html .= '</a>';
						$html .= '</div>';
						if($fam['chill']){
							$mt_rand = mt_rand();

							$html .= ' <label for="un-'.$mt_rand.$rand.'"> [+] </label> <input type="checkbox" id="un-'.$mt_rand.$rand.'">';
						}
					} else {
						$html .= '<div class="indi">';
						$html .= '<a href="'.get_the_permalink($fam['spouse']).'">';
						$html .= get_the_title($fam['spouse']);
						$html .= '</a>';
						$html .= '</div>';
						$html .= '<div class="links">';
						$html .= ' <a href=""><span class="dashicons dashicons-admin-links"></span></a> <a href=""><span class="dashicons dashicons-networking"></span></a>';
						$html .= '</div>';
						if($fam['chill']){
							$mt_rand = mt_rand();
							$html .= ' <label for="'.$mt_rand.$fam['spouse'].'"> [+] </label> <input type="checkbox" id="'.$mt_rand.$fam['spouse'].'">';
						}
					}
					if($fam['chill']){	
						$html .= '<ul class="chills">';
						foreach ($fam['chill'] as $key => $chill) {
							$html .= '<li class="chill"> <b>Children '.($key+1).': </b>';
							$html .= $this->family_style3($chill);
							$html .= '</li>';
						}
						$html .= '</ul>';
					}
				$html .= '</li>';
			}
		
		$html .= '</ul>';
		}
		return $html;
	}


	/**
	 * Register the 
	 *
	 * @since    1.0.0
	 */
	public function get_fam_ref_id($object_id){
		$fam = array();
		$query = new WP_Query(array(
			'post_type' => 'family',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'root',
					'value' => $object_id,
					'compare' => '=',
				)
			) ,
		));
		if ($query->posts) {
			foreach($query->posts as $key => $family) {
				if(get_post_meta($family->ID, 'chill', true) || get_post_meta($family->ID, 'spouse', true)){
					array_push($fam, $family->ID);
				}
			}
		}
		return $fam;
	}


	public function hard_tree($tree){
		$root['ind'] = $tree;
		if($this->get_fam_ref_id($tree)){
			$y = 0;
			foreach ($this->get_fam_ref_id($tree) as $keyf => $fam) {
				$root['fam'][$y]['spouse'] = get_post_meta( $fam, 'spouse', true);
				$root['fam'][$y]['chill'] = get_post_meta( $fam, 'chill', true);
				if($root['fam'][$y]['chill']){
					$x = 0;
					foreach ($root['fam'][$y]['chill'] as $keyc => $chill) {
						$root['fam'][$y]['chill'][$x] = $this->hard_tree($chill);
						$x++;
					}
				}
				$y++;
			}
		}
		return $root;
	}

}