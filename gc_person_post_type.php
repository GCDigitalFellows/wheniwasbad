<?php
if( ! function_exists( 'gc_person_create_post_type' ) ) :
	function gc_person_create_post_type() {
		$labels = array(
			'name' => __( 'People' ),
			'singular_name' => __( 'Person' ),
			'add_new' => __( 'Add Person' ),
			'all_items' => __( 'All People' ),
			'add_new_item' => __( 'Add New Person' ),
			'edit_item' => __( 'Edit Person' ),
			'new_item' => __( 'New Person' ),
			'view_item' => __( 'View Person' ),
			'search_items' => __( 'Search People' ),
			'not_found' => __( 'No people found' ),
			'not_found_in_trash' => __( 'Trash does not contain any people' ),
			'parent_item_colon' => __( 'Parent Person:' ),
			'menu_name' => __( 'People' )
		);
		$args = array(
			'label' => __( 'people_post_type' ),
			'description'			=> __( 'Custom Post Type for People/Staff' ),
			'labels'				=> $labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> true,
			'show_in_admin_bar'		=> true,
			'menu_position'			=> 20,
			'menu_icon'				=> 'dashicons-admin-users',
			'can_export'			=> true,
			'has_archive'			=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> true,
			'capability_type'		=> 'page',
			'query_var' 			=> true,
			'rewrite' 				=> array(
				'slug' 			=> 'people',
				'with_front' 	=> false),
			'hierarchical' 			=> false,
			'supports'				=> array(
				'title',
				'editor',
				//'excerpt',
				'thumbnail',
				//'author',
				//'trackbacks',
				//'custom-fields',
				//'comments',
				'revisions',
				//'page-attributes', // (menu order, hierarchical must be true to show Parent option)
				//'post-formats',
			),
			'taxonomies' 			=> array( 'people_categories' ), // add default post categories and tags
			//'register_meta_box_cb' => 'gc_person_add_post_type_metabox'
		);
		register_post_type( 'gc_person', $args );
		//flush_rewrite_rules();

		register_taxonomy(
			'people_categories', // register custom taxonomy - gc_person category
			'gc_person',
			array(
				'hierarchical' => true,
				'label' => __( 'People Categories' ),
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'people_categories',
					'with_front' => false
				)
			)
		);
		/*register_taxonomy( 'gc_person_tag', // register custom taxonomy - gc_person tag
			'gc_person',
			array( 'hierarchical' => false,
				'label' => __( 'People tags' )
			)
		);*/

	}
	add_action( 'init', 'gc_person_create_post_type' );

	// add some descriptive text below the title
	add_action( 'edit_form_after_title', 'gc_person_edit_form_after_title' );
	function gc_person_edit_form_after_title() {
		$scr = get_current_screen();
  		if ( $scr->post_type !== 'gc_person' )
  			return;
	    echo '<h2>Instructions:</h2><p>A short bio can be added in the content box. Add a picture using the Featured Image on the right side. Enter title, affiliation and contact information in the box below the main editor.';
	}

	/**
	 * Maintain the permalink structure for custom taxonomy
	 * Display custom taxonomy term name before post related to that term
	 * @uses post_type_filter hook
	 */
	/*function filter_post_type_link( $link, $post) {
	    if ( $post->post_type != 'gc_person' )
	        return $link;

	    if ( $cats = get_the_terms( $post->ID, 'people_categories' ) )
	        $link = str_replace( '%people_categories%', array_pop($cats)->slug, $link );
	    return $link;
	}
	add_filter('post_type_link', 'filter_post_type_link', 10, 2);*/

endif; // end of function_exists()

if( ! function_exists( 'gc_person_print_social_links' ) ) : // output
	function gc_person_print_social_links( $links ) {
		foreach ($links as $link) {
			if (isset($link['gc_person_social_network']) && isset($link['gc_person_social_url'])) {
				switch ($link['gc_person_social_network']) {
					case '0':
						//$ico = 'fa fa-comments-o';
						$alt = 'CUNY Academic Commons';
						$btn_class = 'gc_social_cac';
						break;
					case '1':
						//$ico = 'fa fa-facebook';
						$alt = 'Facebook';
						$btn_class = 'gc_social_facebook';
						break;
					case '2':
						//$ico = 'fa fa-twitter';
						$alt = 'Twitter';
						$btn_class = 'gc_social_twitter';
						break;
					case '3':
						//$ico = 'fa fa-graduation-cap';
						$alt = 'Academia.edu';
						$btn_class = 'gc_social_academia';
						break;
					case '4':
						//$ico = 'fa fa-google-plus';
						$alt = 'Google+';
						$btn_class = 'gc_social_gplus';
						break;
					case '5':
						//$ico = 'fa fa-linkedin';
						$alt = 'LinkedIn';
						$btn_class = 'gc_social_linkedin';
						break;
					case '6':
						//$ico = 'fa fa-flask';
						$alt = 'ResearchGate';
						$btn_class = 'gc_social_researchgate';
						break;
					default:
						//$ico = 'fa fa-link';
						$alt = 'External Link';
						$btn_class = 'gc_social_default';
						break;
				}

				echo "<a href='" . $link['gc_person_social_url'] . "' class='" . $btn_class . "' alt='" . $alt . "'>";
				echo "</a>";
			}
		}
	}
endif;

?>