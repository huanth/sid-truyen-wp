<?php
function sid_truyen_breadcrumbs() {
	// Settings
	$separator          = '&gt;';
	$breadcrums_id      = 'breadcrumbs';
	$breadcrums_class   = 'breadcrumbs text-sm text-gray-500 dark:text-gray-400 py-3 mb-4';
	$home_title         = 'Trang Chủ';
	
	// If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
	$custom_taxonomy    = 'product_cat';
	
	// Get the query & post information
	global $post,$wp_query;
	
	// Do not display on the homepage
	if ( !is_front_page() ) {
	
		echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
		
		// Home page
		echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
		echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
		
		// Check for Search FIRST to prevent archive logic errors
		if ( is_search() ) {
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">Kết quả tìm kiếm cho: ' . get_search_query() . '</li>';
		} else if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . post_type_archive_title('', false) . '</li>';
		} else if ( is_archive() && is_tax() ) {
			// Try to get post type from the first post in the query
			$post_type = get_post_type();
			
			// If no posts (empty taxonomy), get from taxonomy object
			if ( ! $post_type ) {
				$queried_object = get_queried_object();
				if ( isset( $queried_object->taxonomy ) ) {
					$taxonomy_obj = get_taxonomy( $queried_object->taxonomy );
					if ( $taxonomy_obj && ! empty( $taxonomy_obj->object_type ) ) {
						$post_type = $taxonomy_obj->object_type[0];
					}
				}
			}
			
			// If it is a custom post type display name and link
			if($post_type && $post_type != 'post') {
				$post_type_object = get_post_type_object($post_type);
				// Check if post type object exists before accessing properties
				if ( $post_type_object && isset( $post_type_object->labels->name ) ) {
					$post_type_archive = get_post_type_archive_link($post_type);
					echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
					echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
				}
			}
			
			$custom_tax_name = get_queried_object()->name;
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . $custom_tax_name . '</li>';
		} else if ( is_single() ) {
			
			// Get post type
			$post_type = get_post_type();
			
			// Special handling for chapter post type - show parent novel instead of archive
			if( $post_type == 'chapter' ) {
				// Get the parent novel
				$parent_novel_id = get_post_meta( get_the_ID(), '_sid_chapter_parent_novel', true );
				
				if ( $parent_novel_id ) {
					// Display parent novel link
					$novel_title = get_the_title( $parent_novel_id );
					$novel_link = get_permalink( $parent_novel_id );
					echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . esc_url( $novel_link ) . '" title="' . esc_attr( $novel_title ) . '">' . esc_html( $novel_title ) . '</a></li>';
					echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
				}
				
				// Display current chapter
				echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
				
			} else if($post_type != 'post') {
				// Other custom post types - show archive link
				$post_type_object = get_post_type_object($post_type);
				$post_type_archive = get_post_type_archive_link($post_type);
				echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
				echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
				
				// Get post category info
				$category = get_the_category();
				
				if(!empty($category)) {
				
					// Get last category post is in
					$last_category = end($category);
					
					// Get parent any categories and create array
					$get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
					$cat_parents = explode(',',$get_cat_parents);
					
					// Loop through parent categories and store in variable $cat_display
					$cat_display = '';
					foreach($cat_parents as $parents) {
						$cat_display .= '<li class="inline-block hover:text-primary transition-colors">'.$parents.'</li>';
						$cat_display .= '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
					}
				
				}
				
				// If it's a custom post type within a custom taxonomy
				$taxonomy_exists = taxonomy_exists($custom_taxonomy);
				if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
					$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
					$cat_id         = $taxonomy_terms[0]->term_id;
					$cat_nicename   = $taxonomy_terms[0]->slug;
					$cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
					$cat_name       = $taxonomy_terms[0]->name;
				}
				
				// Check if the post is in a category
				if(!empty($last_category)) {
					echo $cat_display;
					echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
					
				// Else if post is in a custom taxonomy
				} else if(!empty($cat_id)) {
					echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
					echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
					echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
				} else {
					echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
				}
			} else {
				// Regular post
				// Get post category info
				$category = get_the_category();
				
				if(!empty($category)) {
				
					// Get last category post is in
					$last_category = end($category);
					
					// Get parent any categories and create array
					$get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
					$cat_parents = explode(',',$get_cat_parents);
					
					// Loop through parent categories and store in variable $cat_display
					$cat_display = '';
					foreach($cat_parents as $parents) {
						$cat_display .= '<li class="inline-block hover:text-primary transition-colors">'.$parents.'</li>';
						$cat_display .= '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
					}
				
				}
				
				// Check if the post is in a category
				if(!empty($last_category)) {
					echo $cat_display;
					echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
				} else {
					echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
				}
			}
			
		} else if ( is_category() ) {
			// Category page
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . single_cat_title('', false) . '</li>';
		} else if ( is_page() ) {
			// Standard page
			if( $post->post_parent ){
				// If child page, get parents 
				$anc = get_post_ancestors( $post->ID );
				
				// Get parents in the right order
				$anc = array_reverse($anc);
				
				// Parent page loop
				if ( !isset( $parents ) ) $parents = null;
				foreach ( $anc as $ancestor ) {
					$parents .= '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
					$parents .= '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
				}
				
				// Display parent pages
				echo $parents;
				
				// Current page
				echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
			} else {
				// Just display current page if not parents
				echo '<li class="inline-block text-gray-700 dark:text-gray-300" title="' . get_the_title() . '">' . get_the_title() . '</li>';
			}
		} else if ( is_tag() ) {
			// Tag page
			// Get tag information
			$term_id        = get_query_var('tag_id');
			$taxonomy       = 'post_tag';
			$args           = 'include=' . $term_id;
			$terms          = get_terms( $taxonomy, $args );
			$get_term_id    = $terms[0]->term_id;
			$get_term_slug  = $terms[0]->slug;
			$get_term_name  = $terms[0]->name;
			
			// Display the tag name
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . $get_term_name . '</li>';
		} elseif ( is_day() ) {
			// Day archive
			// Year link
			echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . '</a></li>';
			echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
			
			// Month link
			echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . '</a></li>';
			echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
			
			// Day display
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . get_the_time('jS') . ' ' . get_the_time('M') . '</li>';
		} else if ( is_month() ) {
			// Month Archive
			// Year link
			echo '<li class="inline-block"><a class="hover:text-primary transition-colors" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . '</a></li>';
			echo '<li class="inline-block mx-2 text-gray-400">' . $separator . '</li>';
			
			// Month display
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . get_the_time('M') . '</li>';
		} else if ( is_year() ) {
			// Display year archive
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . get_the_time('Y') . '</li>';
		} else if ( is_author() ) {
			// Auhor archive
			// Get the author name
			global $author;
			$userdata = get_userdata( $author );
			
			// Display author name
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">' . __( 'Tác giả', 'sid-truyen' ) . ': ' . $userdata->display_name . '</li>';
		} else if ( get_query_var('paged') ) {
			// Paginated archives
			echo '<li class="inline-block text-gray-700 dark:text-gray-300">'.__('Trang', 'sid-truyen') . ' ' . get_query_var('paged') . '</li>';
		}
		
		echo '</ul>';
	}
}
