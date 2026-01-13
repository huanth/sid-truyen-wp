<?php

function sid_core_register_post_types() {
    // Register Novel CPT
    $labels_novel = array(
        'name'                  => _x( 'Truyện', 'Post Type General Name', 'sid-truyen-core' ),
        'singular_name'         => _x( 'Truyện', 'Post Type Singular Name', 'sid-truyen-core' ),
        'menu_name'             => __( 'Truyện', 'sid-truyen-core' ),
        'name_admin_bar'        => __( 'Truyện', 'sid-truyen-core' ),
        'archives'              => __( 'Kho Truyện', 'sid-truyen-core' ),
        'attributes'            => __( 'Thuộc tính Truyện', 'sid-truyen-core' ),
        'parent_item_colon'     => __( 'Truyện cha:', 'sid-truyen-core' ),
        'all_items'             => __( 'Tất cả Truyện', 'sid-truyen-core' ),
        'add_new_item'          => __( 'Thêm Truyện Mới', 'sid-truyen-core' ),
        'add_new'               => __( 'Thêm Mới', 'sid-truyen-core' ),
        'new_item'              => __( 'Truyện Mới', 'sid-truyen-core' ),
        'edit_item'             => __( 'Sửa Truyện', 'sid-truyen-core' ),
        'update_item'           => __( 'Cập nhật Truyện', 'sid-truyen-core' ),
        'view_item'             => __( 'Xem Truyện', 'sid-truyen-core' ),
        'view_items'            => __( 'Xem Danh sách Truyện', 'sid-truyen-core' ),
        'search_items'          => __( 'Tìm kiếm Truyện', 'sid-truyen-core' ),
        'not_found'             => __( 'Không tìm thấy', 'sid-truyen-core' ),
        'not_found_in_trash'    => __( 'Không tìm thấy trong thùng rác', 'sid-truyen-core' ),
        'featured_image'        => __( 'Ảnh bìa', 'sid-truyen-core' ),
        'set_featured_image'    => __( 'Đặt ảnh bìa', 'sid-truyen-core' ),
        'remove_featured_image' => __( 'Xóa ảnh bìa', 'sid-truyen-core' ),
        'use_featured_image'    => __( 'Sử dụng làm ảnh bìa', 'sid-truyen-core' ),
    );
    $args_novel = array(
        'label'                 => __( 'Truyện', 'sid-truyen-core' ),
        'description'           => __( 'Post Type for Novels', 'sid-truyen-core' ),
        'labels'                => $labels_novel,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'            => array( 'genre' ), // Linked to Genre taxonomy
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'truyen' ), // URL slug: /truyen/novel-name
    );
    register_post_type( 'novel', $args_novel );

    // Register Chapter CPT
    $labels_chapter = array(
        'name'                  => _x( 'Chương', 'Post Type General Name', 'sid-truyen-core' ),
        'singular_name'         => _x( 'Chương', 'Post Type Singular Name', 'sid-truyen-core' ),
        'menu_name'             => __( 'Chương', 'sid-truyen-core' ),
        'name_admin_bar'        => __( 'Chương', 'sid-truyen-core' ),
        'all_items'             => __( 'Tất cả Chương', 'sid-truyen-core' ),
        'add_new_item'          => __( 'Thêm Chương Mới', 'sid-truyen-core' ),
        'add_new'               => __( 'Thêm Mới', 'sid-truyen-core' ),
        'new_item'              => __( 'Chương Mới', 'sid-truyen-core' ),
        'edit_item'             => __( 'Sửa Chương', 'sid-truyen-core' ),
        'update_item'           => __( 'Cập nhật Chương', 'sid-truyen-core' ),
        'view_item'             => __( 'Xem Chương', 'sid-truyen-core' ),
        'search_items'          => __( 'Tìm kiếm Chương', 'sid-truyen-core' ),
    );
    $args_chapter = array(
        'label'                 => __( 'Chương', 'sid-truyen-core' ),
        'description'           => __( 'Post Type for Job Chapters', 'sid-truyen-core' ),
        'labels'                => $labels_chapter,
        'supports'              => array( 'title', 'editor', 'comments' ), // No Thumbnail needed
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'edit.php?post_type=novel', // Submenu of Novels
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false, // Don't need an archive of all chapters mixed
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => false, // Custom permalink structure
    );
    register_post_type( 'chapter', $args_chapter );
}
add_action( 'init', 'sid_core_register_post_types' );

/**
 * Custom Permalink for Chapters
 * Generates: /truyen/{novel-slug}/{chapter-slug}/
 */
function sid_core_chapter_permalink( $post_link, $post ) {
    if ( $post->post_type === 'chapter' ) {
        $parent_novel_id = get_post_meta( $post->ID, '_sid_chapter_parent_novel', true );
        if ( $parent_novel_id ) {
            $novel_slug = get_post_field( 'post_name', $parent_novel_id );
            if ( $novel_slug ) {
                $post_link = home_url( "truyen/{$novel_slug}/{$post->post_name}/" );
            }
        }
    }
    return $post_link;
}
add_filter( 'post_type_link', 'sid_core_chapter_permalink', 10, 2 );

/**
 * Custom Rewrite Rules for Chapter URLs
 */
function sid_core_add_chapter_rewrite_rules() {
    // New URL pattern: /truyen/{novel-slug}/{chapter-slug}/
    add_rewrite_rule(
        '^truyen/([^/]+)/([^/]+)/?$',
        'index.php?chapter=$matches[2]',
        'top'
    );
    
    // Old URL redirect: /chuong/{chapter-slug}/ -> /truyen/{novel-slug}/{chapter-slug}/
    add_rewrite_rule(
        '^chuong/([^/]+)/?$',
        'index.php?chapter=$matches[1]&old_chapter_url=1',
        'top'
    );
}
add_action( 'init', 'sid_core_add_chapter_rewrite_rules' );

/**
 * Register Query Variables
 */
function sid_core_register_query_vars( $vars ) {
    $vars[] = 'old_chapter_url';
    return $vars;
}
add_filter( 'query_vars', 'sid_core_register_query_vars' );

/**
 * Redirect Old Chapter URLs to New Format
 */
function sid_core_redirect_old_chapter_urls() {
    if ( is_singular( 'chapter' ) && get_query_var( 'old_chapter_url' ) ) {
        $new_url = get_permalink();
        wp_redirect( $new_url, 301 );
        exit;
    }
}
add_action( 'template_redirect', 'sid_core_redirect_old_chapter_urls' );

/**
 * Flush Rewrite Rules Helper
 * Call this function once after making changes or via plugin activation
 */
function sid_core_flush_chapter_rewrites() {
    sid_core_register_post_types();
    sid_core_add_chapter_rewrite_rules();
    flush_rewrite_rules();
}
// Uncomment the line below and visit any page once to flush, then comment it back
// add_action( 'init', 'sid_core_flush_chapter_rewrites' );
