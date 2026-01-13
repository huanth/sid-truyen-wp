<?php

function sid_core_register_taxonomies() {
    $labels = array(
        'name'                       => _x( 'Thể loại', 'Taxonomy General Name', 'sid-truyen-core' ),
        'singular_name'              => _x( 'Thể loại', 'Taxonomy Singular Name', 'sid-truyen-core' ),
        'menu_name'                  => __( 'Thể loại', 'sid-truyen-core' ),
        'all_items'                  => __( 'Tất cả Thể loại', 'sid-truyen-core' ),
        'parent_item'                => __( 'Thể loại cha', 'sid-truyen-core' ),
        'parent_item_colon'          => __( 'Thể loại cha:', 'sid-truyen-core' ),
        'new_item_name'              => __( 'Tên Thể loại Mới', 'sid-truyen-core' ),
        'add_new_item'               => __( 'Thêm Thể loại Mới', 'sid-truyen-core' ),
        'edit_item'                  => __( 'Sửa Thể loại', 'sid-truyen-core' ),
        'update_item'                => __( 'Cập nhật Thể loại', 'sid-truyen-core' ),
        'view_item'                  => __( 'Xem Thể loại', 'sid-truyen-core' ),
        'separate_items_with_commas' => __( 'Phân cách các thể loại bằng dấu phẩy', 'sid-truyen-core' ),
        'add_or_remove_items'        => __( 'Thêm hoặc bớt thể loại', 'sid-truyen-core' ),
        'choose_from_most_used'      => __( 'Chọn từ các thể loại phổ biến', 'sid-truyen-core' ),
        'popular_items'              => __( 'Thể loại Phổ biến', 'sid-truyen-core' ),
        'search_items'               => __( 'Tìm kiếm Thể loại', 'sid-truyen-core' ),
        'not_found'                  => __( 'Không tìm thấy', 'sid-truyen-core' ),
        'no_terms'                   => __( 'Không có thể loại', 'sid-truyen-core' ),
        'items_list'                 => __( 'Danh sách thể loại', 'sid-truyen-core' ),
        'items_list_navigation'      => __( 'Điều hướng danh sách thể loại', 'sid-truyen-core' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => array( 'slug' => 'the-loai' ),
    );
    register_taxonomy( 'genre', array( 'novel' ), $args );
}
add_action( 'init', 'sid_core_register_taxonomies' );
