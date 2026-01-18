<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme Setup
 */
function sid_truyen_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register Navigation Menus
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'sid-truyen' ),
			'footer'  => esc_html__( 'Footer Menu', 'sid-truyen' ),
		)
	);

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
}
add_action( 'after_setup_theme', 'sid_truyen_setup' );

// Include Breadcrumbs
require_once get_template_directory() . '/inc/breadcrumbs.php';

// Include Stats Page
require_once get_template_directory() . '/inc/stats-page.php';

// Include Time Ago Helper
require_once get_template_directory() . '/inc/time-ago.php';

/**
 * Enqueue scripts and styles.
 */
function sid_truyen_scripts() {
	// Enqueue main TailwindCSS build
	// Ensure the file exists before enqueuing to avoid 404s during development if build not run
	$css_version = file_exists( get_template_directory() . '/assets/css/output.css' ) 
		? filemtime( get_template_directory() . '/assets/css/output.css' ) 
		: '1.0.0';

	wp_enqueue_style( 'sid-truyen-style', get_template_directory_uri() . '/assets/css/output.css', array(), $css_version );

	// Enqueue theme logic
	wp_enqueue_script( 'sid-truyen-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sid_truyen_scripts' );

/**
 * Add "Tên Truyện" column to Chapter admin list
 */
function sid_truyen_add_chapter_novel_column( $columns ) {
	// Insert "Novel Name" column after title
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( $key === 'title' ) {
			$new_columns['novel_name'] = __( 'Tên Truyện', 'sid-truyen' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_chapter_posts_columns', 'sid_truyen_add_chapter_novel_column' );

/**
 * Display novel name in the custom column
 */
function sid_truyen_display_chapter_novel_column( $column, $post_id ) {
	if ( $column === 'novel_name' ) {
		$parent_novel_id = get_post_meta( $post_id, '_sid_chapter_parent_novel', true );
		
		if ( $parent_novel_id ) {
			$novel_title = get_the_title( $parent_novel_id );
			$edit_link = get_edit_post_link( $parent_novel_id );
			
			if ( $novel_title && $edit_link ) {
				echo '<a href="' . esc_url( $edit_link ) . '">' . esc_html( $novel_title ) . '</a>';
			} else {
				echo '—';
			}
		} else {
			echo '<span style="color: #999;">Chưa gán</span>';
		}
	}
}
add_action( 'manage_chapter_posts_custom_column', 'sid_truyen_display_chapter_novel_column', 10, 2 );

/**
 * Make "Tên Truyện" column sortable
 */
function sid_truyen_chapter_novel_column_sortable( $columns ) {
	$columns['novel_name'] = 'novel_name';
	return $columns;
}
add_filter( 'manage_edit-chapter_sortable_columns', 'sid_truyen_chapter_novel_column_sortable' );

/**
 * Handle sorting by novel name
 */
function sid_truyen_chapter_novel_column_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'novel_name' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_sid_chapter_parent_novel' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'sid_truyen_chapter_novel_column_orderby' );



/**
 * Add custom columns to Novel admin list
 */
function sid_truyen_add_novel_columns( $columns ) {
	// Remove default author column (shows WP post author)
	unset( $columns['author'] );
	
	// Insert custom columns after title
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( $key === 'title' ) {
			$new_columns['novel_author'] = __( 'Tác giả', 'sid-truyen' );
			$new_columns['chapter_count'] = __( 'Số chương', 'sid-truyen' );
			$new_columns['status'] = __( 'Trạng thái', 'sid-truyen' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_novel_posts_columns', 'sid_truyen_add_novel_columns' );

/**
 * Display content in custom columns
 */
function sid_truyen_display_novel_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'novel_author':
			$author = get_post_meta( $post_id, '_sid_novel_author', true );
			if ( $author ) {
				echo '<strong>' . esc_html( $author ) . '</strong>';
			} else {
				echo '<span style="color: #999;">Chưa có</span>';
			}
			break;
			
		case 'chapter_count':
			$chapter_count = get_posts( array(
				'post_type' => 'chapter',
				'meta_key' => '_sid_chapter_parent_novel',
				'meta_value' => $post_id,
				'posts_per_page' => -1,
				'fields' => 'ids'
			) );
			
			$count = count( $chapter_count );
			if ( $count > 0 ) {
				echo '<span class="dashicons dashicons-book-alt"></span> ';
				echo '<strong>' . $count . '</strong> chương';
			} else {
				echo '<span style="color: #999;">0 chương</span>';
			}
			break;
			
		case 'status':
			$status = get_post_meta( $post_id, '_sid_novel_status', true );
			
			$status_labels = array(
				'ongoing' => array( 'label' => 'Đang ra', 'color' => '#0073aa' ),
				'completed' => array( 'label' => 'Hoàn thành', 'color' => '#46b450' ),
				'paused' => array( 'label' => 'Tạm dừng', 'color' => '#f0b849' ),
			);
			
			if ( $status && isset( $status_labels[ $status ] ) ) {
				$info = $status_labels[ $status ];
				echo '<span style="background: ' . $info['color'] . '; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; display: inline-block;">';
				echo esc_html( $info['label'] );
				echo '</span>';
			} else {
				echo '<span style="color: #999;">Chưa đặt</span>';
			}
			break;
	}
}
add_action( 'manage_novel_posts_custom_column', 'sid_truyen_display_novel_columns', 10, 2 );

/**
 * Make custom columns sortable
 */
function sid_truyen_novel_columns_sortable( $columns ) {
	$columns['novel_author'] = 'novel_author';
	$columns['chapter_count'] = 'chapter_count';
	$columns['status'] = 'status';
	return $columns;
}
add_filter( 'manage_edit-novel_sortable_columns', 'sid_truyen_novel_columns_sortable' );

/**
 * Handle sorting for custom columns
 */
function sid_truyen_novel_columns_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	$orderby = $query->get( 'orderby' );
	
	switch ( $orderby ) {
		case 'novel_author':
			$query->set( 'meta_key', '_sid_novel_author' );
			$query->set( 'orderby', 'meta_value' );
			break;
			
		case 'status':
			$query->set( 'meta_key', '_sid_novel_status' );
			$query->set( 'orderby', 'meta_value' );
			break;
			
		case 'chapter_count':
			// Chapter count is more complex - would need a custom query
			// For now, just sort by novel ID as a placeholder
			$query->set( 'orderby', 'ID' );
			break;
	}
}
add_action( 'pre_get_posts', 'sid_truyen_novel_columns_orderby' );

/**
 * Add author filter dropdown to Novel admin list
 */
function sid_truyen_novel_author_filter() {
	global $typenow, $wpdb;
	
	if ( $typenow !== 'novel' ) {
		return;
	}
	
	// Get all unique novel authors
	$authors = $wpdb->get_col( 
		"SELECT DISTINCT meta_value 
		FROM {$wpdb->postmeta} 
		WHERE meta_key = '_sid_novel_author' 
		AND meta_value != '' 
		ORDER BY meta_value ASC"
	);
	
	if ( empty( $authors ) ) {
		return;
	}
	
	$current_author = isset( $_GET['novel_author_filter'] ) ? sanitize_text_field( $_GET['novel_author_filter'] ) : '';
	
	echo '<select name="novel_author_filter" id="novel_author_filter">';
	echo '<option value="">' . __( 'Tất cả tác giả', 'sid-truyen' ) . '</option>';
	
	foreach ( $authors as $author ) {
		printf(
			'<option value="%s"%s>%s</option>',
			esc_attr( $author ),
			selected( $current_author, $author, false ),
			esc_html( $author )
		);
	}
	
	echo '</select>';
}
add_action( 'restrict_manage_posts', 'sid_truyen_novel_author_filter' );

/**
 * Handle author filter query
 */
function sid_truyen_novel_author_filter_query( $query ) {
	global $pagenow, $typenow;
	
	// Only apply to main query on novel edit page
	if ( $pagenow !== 'edit.php' || $typenow !== 'novel' || ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	if ( ! isset( $_GET['novel_author_filter'] ) || empty( $_GET['novel_author_filter'] ) ) {
		return;
	}
	
	$author = sanitize_text_field( $_GET['novel_author_filter'] );
	
	$query->set( 'meta_query', array(
		array(
			'key' => '_sid_novel_author',
			'value' => $author,
			'compare' => '='
		)
	) );
}
add_filter( 'parse_query', 'sid_truyen_novel_author_filter_query' );

/**
 * Add novel filter dropdown to Chapter admin list
 */
function sid_truyen_chapter_novel_filter() {
	global $typenow;
	
	if ( $typenow !== 'chapter' ) {
		return;
	}
	
	// Get all published novels
	$novels = get_posts( array(
		'post_type' => 'novel',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'orderby' => 'title',
		'order' => 'ASC'
	) );
	
	if ( empty( $novels ) ) {
		return;
	}
	
	$current_novel = isset( $_GET['chapter_novel_filter'] ) ? intval( $_GET['chapter_novel_filter'] ) : '';
	
	echo '<select name="chapter_novel_filter" id="chapter_novel_filter">';
	echo '<option value="">' . __( 'Tất cả truyện', 'sid-truyen' ) . '</option>';
	
	foreach ( $novels as $novel ) {
		printf(
			'<option value="%s"%s>%s</option>',
			esc_attr( $novel->ID ),
			selected( $current_novel, $novel->ID, false ),
			esc_html( $novel->post_title )
		);
	}
	
	echo '</select>';
}
add_action( 'restrict_manage_posts', 'sid_truyen_chapter_novel_filter' );

/**
 * Handle novel filter query for chapters
 */
function sid_truyen_chapter_novel_filter_query( $query ) {
	global $pagenow, $typenow;
	
	// Only apply to main query on chapter edit page
	if ( $pagenow !== 'edit.php' || $typenow !== 'chapter' || ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	if ( ! isset( $_GET['chapter_novel_filter'] ) || empty( $_GET['chapter_novel_filter'] ) ) {
		return;
	}
	
	$novel_id = intval( $_GET['chapter_novel_filter'] );
	
	$query->set( 'meta_query', array(
		array(
			'key' => '_sid_chapter_parent_novel',
			'value' => $novel_id,
			'compare' => '='
		)
	) );
}
add_filter( 'parse_query', 'sid_truyen_chapter_novel_filter_query' );

/**
 * Track post views for novels and chapters
 */
function sid_truyen_track_post_views() {
	if ( ! is_singular() ) {
		return;
	}
	
	global $post;
	
	// Only track views for novel and chapter post types
	if ( ! in_array( $post->post_type, array( 'novel', 'chapter' ) ) ) {
		return;
	}
	
	// Don't count views from bots or admins
	// if ( is_admin() || current_user_can( 'manage_options' ) ) {
	// 	return;
	// }
	
	// Prevent counting same user multiple times in short period
	$cookie_name = 'sid_viewed_' . $post->ID;
	if ( isset( $_COOKIE[ $cookie_name ] ) ) {
		return;
	}
	
	// Set cookie for 1 hour to prevent spam
	setcookie( $cookie_name, '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
	
	// Get current view count
	$meta_key = '_sid_' . $post->post_type . '_views';
	$count = get_post_meta( $post->ID, $meta_key, true );
	$count = $count ? intval( $count ) : 0;
	
	// Increment view count
	update_post_meta( $post->ID, $meta_key, $count + 1 );
	
	// Also update total novel views when chapter is viewed
	if ( $post->post_type === 'chapter' ) {
		$novel_id = get_post_meta( $post->ID, '_sid_chapter_parent_novel', true );
		if ( $novel_id ) {
			$novel_views = get_post_meta( $novel_id, '_sid_novel_views', true );
			$novel_views = $novel_views ? intval( $novel_views ) : 0;
			update_post_meta( $novel_id, '_sid_novel_views', $novel_views + 1 );
		}
	}
}
add_action( 'wp', 'sid_truyen_track_post_views' );

/**
 * Get formatted view count
 */
function sid_truyen_get_views( $post_id, $post_type = 'novel' ) {
	$meta_key = '_sid_' . $post_type . '_views';
	$count = get_post_meta( $post_id, $meta_key, true );
	$count = $count ? intval( $count ) : 0;
	
	// Format large numbers
	if ( $count >= 1000000 ) {
		return number_format( $count / 1000000, 1 ) . 'M';
	} elseif ( $count >= 1000 ) {
		return number_format( $count / 1000, 1 ) . 'K';
	}
	
	return number_format( $count );
}

/**
 * Add views column to Novel admin list
 */
function sid_truyen_add_novel_views_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( $key === 'status' ) {
			$new_columns['views'] = __( 'Lượt xem', 'sid-truyen' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_novel_posts_columns', 'sid_truyen_add_novel_views_column', 20 );

/**
 * Display views in Novel admin column
 */
function sid_truyen_display_novel_views_column( $column, $post_id ) {
	if ( $column === 'views' ) {
		$views = get_post_meta( $post_id, '_sid_novel_views', true );
		$views = $views ? intval( $views ) : 0;
		
		echo '<span class="dashicons dashicons-visibility"></span> ';
		echo '<strong>' . number_format( $views ) . '</strong>';
	}
}
add_action( 'manage_novel_posts_custom_column', 'sid_truyen_display_novel_views_column', 20, 2 );

/**
 * Make views column sortable
 */
function sid_truyen_novel_views_sortable( $columns ) {
	$columns['views'] = 'views';
	return $columns;
}
add_filter( 'manage_edit-novel_sortable_columns', 'sid_truyen_novel_views_sortable', 20 );

/**
 * Handle views sorting
 */
function sid_truyen_novel_views_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	if ( 'views' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_sid_novel_views' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'sid_truyen_novel_views_orderby', 20 );

/**
 * Add views column to Chapter admin list
 */
function sid_truyen_add_chapter_views_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( $key === 'novel_name' ) {
			$new_columns['views'] = __( 'Lượt xem', 'sid-truyen' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_chapter_posts_columns', 'sid_truyen_add_chapter_views_column', 20 );

/**
 * Display views in Chapter admin column
 */
function sid_truyen_display_chapter_views_column( $column, $post_id ) {
	if ( $column === 'views' ) {
		$views = get_post_meta( $post_id, '_sid_chapter_views', true );
		$views = $views ? intval( $views ) : 0;
		
		echo '<span class="dashicons dashicons-visibility"></span> ';
		echo '<strong>' . number_format( $views ) . '</strong>';
	}
}
add_action( 'manage_chapter_posts_custom_column', 'sid_truyen_display_chapter_views_column', 20, 2 );

/**
 * Make chapter views column sortable
 */
function sid_truyen_chapter_views_sortable( $columns ) {
	$columns['views'] = 'views';
	return $columns;
}
add_filter( 'manage_edit-chapter_sortable_columns', 'sid_truyen_chapter_views_sortable', 20 );

/**
 * Handle chapter views sorting
 */
function sid_truyen_chapter_views_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	if ( 'views' === $query->get( 'orderby' ) && get_query_var( 'post_type' ) === 'chapter' ) {
		$query->set( 'meta_key', '_sid_chapter_views' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'sid_truyen_chapter_views_orderby', 20 );

/**
 * Add Stats to Admin Bar
 */
function sid_truyen_add_stats_to_admin_bar( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Add main stats menu
	$args = array(
		'id'    => 'sid-stats',
		'title' => '<span class="ab-icon dashicons dashicons-chart-bar"></span><span class="ab-label">Thống kê</span>',
		'href'  => admin_url( 'admin.php?page=sid-truyen-stats' ),
		'meta'  => array(
			'title' => __( 'Thống kê Truyện', 'sid-truyen' ),
		),
	);
	$wp_admin_bar->add_node( $args );
	
	// Add quick links submenu
	$wp_admin_bar->add_node( array(
		'parent' => 'sid-stats',
		'id'     => 'sid-stats-novels',
		'title'  => __( 'Tất cả truyện', 'sid-truyen' ),
		'href'   => admin_url( 'edit.php?post_type=novel' ),
	) );
	
	$wp_admin_bar->add_node( array(
		'parent' => 'sid-stats',
		'id'     => 'sid-stats-chapters',
		'title'  => __( 'Tất cả chương', 'sid-truyen' ),
		'href'   => admin_url( 'edit.php?post_type=chapter' ),
	) );
	
	$wp_admin_bar->add_node( array(
		'parent' => 'sid-stats',
		'id'     => 'sid-stats-add-novel',
		'title'  => __( '+ Thêm truyện mới', 'sid-truyen' ),
		'href'   => admin_url( 'post-new.php?post_type=novel' ),
	) );
}
add_action( 'admin_bar_menu', 'sid_truyen_add_stats_to_admin_bar', 100 );

/**
 * Customize Document Title for Filtered Archive Pages
 */
/**
 * Register custom query variables
 */
function sid_truyen_register_query_vars( $vars ) {
    $vars[] = 'v_sort';
    $vars[] = 'v_status';
    return $vars;
}
add_filter( 'query_vars', 'sid_truyen_register_query_vars' );

/**
 * Add custom rewrite rules for Hot and Completed/Full stories
 */
function sid_truyen_add_rewrite_rules() {
    // Hot Stories (Truyện Hot)
    add_rewrite_rule(
        'truyen-hot/page/([0-9]+)/?$',
        'index.php?post_type=novel&v_sort=views&paged=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        'truyen-hot/?$',
        'index.php?post_type=novel&v_sort=views',
        'top'
    );

    // Completed Stories (Truyện Full/Hoàn Thành)
    add_rewrite_rule(
        'truyen-hoan-thanh/page/([0-9]+)/?$',
        'index.php?post_type=novel&v_status=completed&paged=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        'truyen-hoan-thanh/?$',
        'index.php?post_type=novel&v_status=completed',
        'top'
    );
}
add_action( 'init', 'sid_truyen_add_rewrite_rules' );

/**
 * Customize Global Document Title for Filtered Archive Pages
 */
// Customize Global Document Title for Filtered Archive Pages
function sid_truyen_pre_get_document_title( $title ) {
    if ( is_front_page() || is_home() ) {
        return 'Truyện Hay - Đọc truyện online miễn phí, cập nhật liên tục';
    }
    if ( is_post_type_archive( 'novel' ) ) {
        if ( get_query_var( 'v_sort' ) === 'views' ) {
            return 'Truyện Hot - ' . get_bloginfo( 'name' );
        } elseif ( get_query_var( 'v_status' ) === 'completed' ) {
            return 'Truyện đã hoàn thành - ' . get_bloginfo( 'name' );
        } else {
            return 'Tất cả truyện - ' . get_bloginfo( 'name' );
        }

        if ( is_paged() ) {
            // WordPress automatically adds page number to title usually, but let's be safe if it doesn't
            // actually, pre_get_document_title often replaces the whole thing.
            // Let's rely on WP default behavior for paged titles unless we need strictly custom format
            // Just returning the base string is usually better, WP appends "Page X"
        }
    }
    if ( is_tax( 'genre' ) ) {
        $term = get_queried_object();
        return 'Truyện ' . $term->name . ' hay nhất chọn lọc - ' . get_bloginfo( 'name' );
    }
    
    // Custom Title for Single Chapter: Chapter Name - Novel Name - Site Name
    if ( is_singular( 'chapter' ) ) {
        $parent_novel_id = get_post_meta( get_the_ID(), '_sid_chapter_parent_novel', true );
        if ( $parent_novel_id ) {
            return get_the_title() . ' - ' . get_the_title( $parent_novel_id ) . ' - ' . get_bloginfo( 'name' );
        }
    }

    return $title;
}
add_filter( 'pre_get_document_title', 'sid_truyen_pre_get_document_title', 20 );

/**
 * Filter search to only search by title for 'novel' post type
 */
function sid_truyen_search_by_title_only( $search, $wp_query ) {
    global $wpdb;
    
    if ( empty( $search ) ) {
        return $search;
    }
    
    // Check if it's main query, is search, and post type is novel
    if ( ! is_admin() && $wp_query->is_main_query() && $wp_query->is_search() ) {
        $post_type = $wp_query->get( 'post_type' );
        
        if ( $post_type === 'novel' ) {
            $q = $wp_query->query_vars;
            $n = ! empty( $q['exact'] ) ? '' : '%';
            
            $search = $wpdb->prepare( 
                " AND {$wpdb->posts}.post_title LIKE %s ",
                $n . $wpdb->esc_like( $q['s'] ) . $n 
            );
            
            // Add filter for post type explicitly in WHERE if not handled by standard query part (usually safe to just replace search logic)
            // Note: WordPress will still append AND post_type = 'novel' automatically because query_vars has it.
        }
    }
    
    return $search;
}
add_filter( 'posts_search', 'sid_truyen_search_by_title_only', 10, 2 );

/**
 * Disable canonical redirect for Novel pagination to allow custom chapter pagination
 */
function sid_truyen_disable_novel_redirect( $redirect_url ) {
    // Check if we are potentially on a novel pagination URL
    // Pattern: /truyen/something/page/number
    // Also include new rules for truyen-hot and truyen-hoan-thanh
    if ( preg_match( '#/truyen(-hot|-hoan-thanh)?/[^/]+/page/\d+#', $_SERVER['REQUEST_URI'] ) 
         || preg_match( '#/truyen-(hot|hoan-thanh)/page/\d+#', $_SERVER['REQUEST_URI'] ) ) {
        return false;
    }
    return $redirect_url;
}
add_filter( 'redirect_canonical', 'sid_truyen_disable_novel_redirect', 9999 );

/**
 * Add og:image for single chapter pages (using parent Novel cover)
 */
function sid_truyen_add_chapter_og_image() {
    if ( is_singular( 'chapter' ) ) {
        $parent_novel_id = get_post_meta( get_the_ID(), '_sid_chapter_parent_novel', true );
        
        if ( $parent_novel_id && has_post_thumbnail( $parent_novel_id ) ) {
            $image_url = get_the_post_thumbnail_url( $parent_novel_id, 'full' );
            if ( $image_url ) {
                echo '<meta property="og:image" content="' . esc_attr( $image_url ) . '" />' . "\n";
                echo '<meta name="twitter:image" content="' . esc_attr( $image_url ) . '" />' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'sid_truyen_add_chapter_og_image' );
/**
 * Modify Main Query for Archives
 */
function sid_truyen_modify_archive_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_post_type_archive( 'novel' ) || is_tax( 'genre' ) || is_search() ) {
        if ( is_search() ) {
            $query->set( 'post_type', 'novel' );
        }
        $query->set( 'posts_per_page', 36 );

        // Handle Sort by Views
        if ( $query->get( 'v_sort' ) === 'views' ) {
            $query->set( 'meta_key', '_sid_novel_views' );
            $query->set( 'orderby', 'meta_value_num' );
        }

        // Handle Filter by Status
        if ( $query->get( 'v_status' ) === 'completed' ) {
            $meta_query = $query->get('meta_query');
            if( !is_array($meta_query) ) {
                $meta_query = array();
            }
            $meta_query[] = array(
                'key' => '_sid_novel_status',
                'value' => 'completed',
                'compare' => '='
            );
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'sid_truyen_modify_archive_query' );
/**
 * SEO & Social Meta Tags
 * Handles Open Graph and Twitter Cards for Homepage, Novels, Chapters, and Archives.
 */
function sid_truyen_seo_meta_tags() {
    global $post;

    // Default Values
    $site_name = get_bloginfo('name');
    $title = get_bloginfo('name');
    $description = get_bloginfo('description');
    $image = get_template_directory_uri() . '/assets/images/logo.png'; // Default fallback
    $url = home_url();
    $type = 'website';

    // 1. Homepage
    if ( is_front_page() || is_home() ) {
        $title = $site_name . ' - Đọc Truyện Chữ Online';
        $desc = get_bloginfo('description');
        if ( ! $desc || $desc == 'Just another WordPress site' ) {
            $description = "Kho truyện chữ hàng đầu với hàng ngàn đầu truyện hấp dẫn đa dạng thể loại (Tiên Hiệp, Kiếm Hiệp, Ngôn Tình). Cập nhật liên tục, giao diện thân thiện, đọc truyện miễn phí.";
        } else {
            $description = $desc;
        }
        $url = home_url();
    }

    // 2. Single Novel
    elseif ( is_singular('novel') ) {
        $title = get_the_title();
        $type = 'article';
        $url = get_permalink();
        
        // Description: Excerpt or trimmed content
        if ( has_excerpt() ) {
            $description = get_the_excerpt();
        } else {
            $content = wp_strip_all_tags( get_the_content() );
            $description = mb_substr( $content, 0, 160 ) . '...';
        }

        // Image: Featured Image
        if ( has_post_thumbnail() ) {
            $image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
        }
    }

    // 3. Single Chapter
    elseif ( is_singular('chapter') ) {
        $type = 'article';
        $url = get_permalink();
        
        // Parent Novel Info
        $parent_id = get_post_meta( get_the_ID(), '_sid_chapter_parent_novel', true );
        $parent_title = $parent_id ? get_the_title( $parent_id ) : '';
        
        // Title: Chapter Name - Novel Name
        $title = get_the_title() . ( $parent_title ? ' - ' . $parent_title : '' );

        // Description
        $description = "Đọc truyện " . $parent_title . " - " . get_the_title() . " online mới nhất, cập nhật nhanh nhất tại " . $site_name;

        // Image: Parent Novel Cover
        if ( $parent_id && has_post_thumbnail( $parent_id ) ) {
            $image = get_the_post_thumbnail_url( $parent_id, 'full' );
        }
    }

    // 4. Search Results
    elseif ( is_search() ) {
        $title = 'Kết quả tìm kiếm: ' . get_search_query();
        $description = 'Kết quả tìm kiếm cho từ khóa "' . get_search_query() . '" tại ' . $site_name;
        $url = home_url( '?s=' . get_search_query() );
    }

    // 5. Post Type Archives & Custom Pages
    elseif ( is_post_type_archive( 'novel' ) ) {
        $title = post_type_archive_title('', false);
        $url = get_post_type_archive_link( 'novel' );
        $description = 'Danh sách truyện mới nhất, cập nhật liên tục tại ' . $site_name;
        
        // Custom Canonical URLs and Meta for Hot & Completed Pages
        if ( get_query_var( 'v_sort' ) === 'views' ) {
            $url = home_url( '/truyen-hot/' );
            $title = 'Truyện Hot';
            $description = 'Danh sách truyện hot, truyện xem nhiều nhất, được yêu thích nhất tháng tại ' . $site_name;
        } elseif ( get_query_var( 'v_status' ) === 'completed' ) {
            $url = home_url( '/truyen-hoan-thanh/' );
            $title = 'Truyện đã hoàn thành';
            $description = 'Danh sách truyện đã hoàn thành (Full), truyện ngôn tình, tiên hiệp đã ra đủ chương tại ' . $site_name;
        }

        if ( is_paged() ) {
            $paged = get_query_var( 'paged' );
            $url = user_trailingslashit( trailingslashit( $url ) . 'page/' . $paged );
            $title .= ' - Trang ' . $paged;
            $description .= ' - Trang ' . $paged;
        }
    }

    // 6. Taxonomies (Genre, Category, etc.)
    elseif ( is_archive() ) {
        $title = get_the_archive_title();
        $url = get_term_link( get_queried_object() );
        $type = 'object';

        if ( term_description() ) {
            $description = wp_strip_all_tags( term_description() );
        } else {
            $description = 'Danh sách truyện ' . $title . ' mới nhất tại ' . $site_name;
        }
    }

    // Clean up
    $title = esc_attr( $title );
    $description = esc_attr( $description );
    $image = esc_url( $image );
    $url = esc_url( $url );
    $site_name = esc_attr( $site_name );

    // Output Meta Tags
    echo "\n<!-- SEO & Social Meta Tags -->\n";
    
    // Open Graph
    echo '<meta name="description" content="' . $description . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . $site_name . '" />' . "\n";
    echo '<meta property="og:type" content="' . $type . '" />' . "\n";
    echo '<meta property="og:title" content="' . $title . '" />' . "\n";
    echo '<meta property="og:description" content="' . $description . '" />' . "\n";
    echo '<meta property="og:url" content="' . $url . '" />' . "\n";
    echo '<meta property="og:image" content="' . $image . '" />' . "\n";
    echo '<meta property="og:image:width" content="1200" />' . "\n";
    echo '<meta property="og:image:height" content="630" />' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . $title . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . $description . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . $image . '" />' . "\n";
    
    echo "<!-- End SEO Meta Tags -->\n\n";
}
add_action( 'wp_head', 'sid_truyen_seo_meta_tags', 5 );
/**
 * Ebook Download Feature (DOC Format)
 * Handles requests for downloading novels and chapters as .doc files.
 */
function sid_truyen_handle_ebook_download() {
    if ( isset( $_GET['ebook_download'] ) && isset( $_GET['post_id'] ) ) {
        $post_id = intval( $_GET['post_id'] );
        $post_type = get_post_type( $post_id );
        
        if ( ! $post_id || ! in_array( $post_type, ['novel', 'chapter'] ) ) {
            return;
        }

        // Set Headers for DOC Download (HTML format)
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . sanitize_file_name(get_the_title($post_id)) . '.doc"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // Start HTML Document
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' . esc_html(get_the_title($post_id)) . '</title>';
        echo '<style>body{font-family:"Times New Roman",serif;font-size:14pt;line-height:1.6;}h1{font-size:24pt;font-weight:bold;text-align:center;margin:20pt 0;}h2{font-size:18pt;font-weight:bold;margin:15pt 0 10pt;page-break-before:always;}p{margin:10pt 0;text-align:justify;}.meta{font-size:12pt;color:#666;text-align:center;}</style></head><body>';

        // 1. Single Chapter Download
        if ( $post_type === 'chapter' ) {
            $parent_id = get_post_meta( $post_id, '_sid_chapter_parent_novel', true );
            $parent_title = $parent_id ? get_the_title( $parent_id ) : '';
            
            echo '<h1>' . esc_html($parent_title) . '</h1>';
            echo '<h2 style="page-break-before:avoid;">' . esc_html(get_the_title($post_id)) . '</h2>';
            echo '<p class="meta">Nguồn: ' . esc_html(home_url()) . '</p>';
            echo '<p class="meta">Link: ' . esc_html(get_permalink($post_id)) . '</p><br/>';
            
            $content = wpautop(get_post_field('post_content', $post_id));
            echo $content;
        }

        // 2. Full Novel Download
        elseif ( $post_type === 'novel' ) {
            $novel_title = get_the_title( $post_id );
            $author = get_post_meta( $post_id, '_sid_novel_author', true );
            
            echo '<h1>' . esc_html($novel_title) . '</h1>';
            echo '<p class="meta"><strong>Tác giả:</strong> ' . esc_html($author ? $author : 'Đang cập nhật') . '</p>';
            echo '<p class="meta"><strong>Nguồn:</strong> ' . esc_html(home_url()) . '</p>';
            echo '<p class="meta">Link: ' . esc_html(get_permalink($post_id)) . '</p><br/>';
            
            echo '<h2 style="page-break-before:avoid;">GIỚI THIỆU</h2>';
            $intro = wpautop(get_post_field('post_content', $post_id));
            echo $intro;

            // Query All Chapters
            $chapters = get_posts(array(
                'post_type' => 'chapter',
                'meta_key' => '_sid_chapter_parent_novel',
                'meta_value' => $post_id,
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'ASC' // Oldest to Newest
            ));

            if ( $chapters ) {
                foreach ( $chapters as $chapter ) {
                    echo '<h2>' . esc_html(get_the_title($chapter->ID)) . '</h2>';
                    $chapter_content = wpautop($chapter->post_content);
                    echo $chapter_content;
                    
                    // Prevent timeout / memory issues for large novels
                    if ( ob_get_level() > 0 ) ob_flush();
                    flush();
                }
            } else {
                echo '<p>Truyện này chưa có chương nào.</p>';
            }
        }

        echo '</body></html>';
        exit;
    }
}
add_action( 'init', 'sid_truyen_handle_ebook_download' );

/**
 * AJAX Handler for Chapter Search
 */
function sid_truyen_ajax_search_chapters() {
	// Verify nonce for security
	check_ajax_referer( 'chapter_search_nonce', 'nonce' );
	
	$search_term = isset( $_POST['search_term'] ) ? sanitize_text_field( $_POST['search_term'] ) : '';
	$novel_id = isset( $_POST['novel_id'] ) ? intval( $_POST['novel_id'] ) : 0;
	
	if ( ! $novel_id || empty( $search_term ) ) {
		wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
	}
	
	// Convert search term to lowercase for case-insensitive Vietnamese search
	$search_term_lower = mb_strtolower( $search_term, 'UTF-8' );
	
	// Query ALL chapters of this novel first
	$args = array(
		'post_type' => 'chapter',
		'posts_per_page' => -1, // Get all chapters
		'meta_query' => array(
			array(
				'key' => '_sid_chapter_parent_novel',
				'value' => $novel_id,
				'compare' => '='
			)
		),
		'orderby' => 'date',
		'order' => 'DESC' // Newest first
	);
	
	$chapter_query = new WP_Query( $args );
	$chapters = array();
	
	if ( $chapter_query->have_posts() ) {
		while ( $chapter_query->have_posts() ) {
			$chapter_query->the_post();
			
			// Filter by title in PHP - only include if title contains search term
			$chapter_title = get_the_title();
			
			// Case-insensitive search in title (supports Vietnamese Unicode)
			$chapter_title_lower = mb_strtolower( $chapter_title, 'UTF-8' );
			
			if ( mb_strpos( $chapter_title_lower, $search_term_lower ) !== false ) {
				// Check if chapter is new (posted within 24 hours)
				$post_time = get_the_time('U');
				$current_time = current_time('timestamp');
				$time_diff = $current_time - $post_time;
				$is_new = ($time_diff < 86400);
				
				$chapters[] = array(
					'title' => $chapter_title,
					'permalink' => get_permalink(),
					'is_new' => $is_new
				);
			}
		}
		wp_reset_postdata();
	}
	
	wp_send_json_success( array( 'chapters' => $chapters ) );
}
add_action( 'wp_ajax_search_chapters', 'sid_truyen_ajax_search_chapters' );
add_action( 'wp_ajax_nopriv_search_chapters', 'sid_truyen_ajax_search_chapters' );
