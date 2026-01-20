<?php

// Display admin notice for required novel field
function sid_core_chapter_novel_required_notice() {
    if ( isset( $_GET['novel_required'] ) && $_GET['novel_required'] == '1' ) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><strong>Lỗi:</strong> Vui lòng chọn truyện gốc cho chương này trước khi lưu.</p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'sid_core_chapter_novel_required_notice' );

// Enqueue Select2 for Chapter Admin
function sid_core_enqueue_select2_assets( $hook ) {
    // Only load on chapter edit screens
    if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
        return;
    }
    
    $screen = get_current_screen();
    if ( ! $screen || 'chapter' !== $screen->post_type ) {
        return;
    }
    
    // Enqueue Select2 CSS
    wp_enqueue_style(
        'select2',
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
        array(),
        '4.1.0'
    );
    
    // Enqueue Select2 JS
    wp_enqueue_script(
        'select2',
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
        array( 'jquery' ),
        '4.1.0',
        true
    );
    
    // Initialize Select2
    wp_add_inline_script( 'select2', '
        jQuery(document).ready(function($) {
            $("#sid_chapter_parent_novel").select2({
                placeholder: "Chọn một bộ truyện...",
                allowClear: false,
                width: "100%"
            });
        });
    ' );
}
add_action( 'admin_enqueue_scripts', 'sid_core_enqueue_select2_assets' );

// Add Meta Boxes
function sid_core_add_meta_boxes() {
    // Novel Meta
    add_meta_box(
        'sid_novel_info',
        __( 'Thông tin Truyện', 'sid-truyen-core' ),
        'sid_novel_info_callback',
        'novel',
        'side'
    );

    // Chapter Meta
    add_meta_box(
        'sid_chapter_parent',
        __( 'Truyện gốc', 'sid-truyen-core' ),
        'sid_chapter_parent_callback',
        'chapter',
        'side',
        'high'
    );
    
    // Chapter Cover Image (from parent novel)
    add_meta_box(
        'sid_chapter_cover',
        __( 'Ảnh bìa truyện', 'sid-truyen-core' ),
        'sid_chapter_cover_callback',
        'chapter',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'sid_core_add_meta_boxes' );

// Novel Meta Callback
function sid_novel_info_callback( $post ) {
    wp_nonce_field( 'sid_novel_info_save', 'sid_novel_info_nonce' );

    $author_name = get_post_meta( $post->ID, '_sid_novel_author', true );
    $status      = get_post_meta( $post->ID, '_sid_novel_status', true );
    ?>
    <p>
        <label for="sid_novel_author"><?php _e( 'Tên Tác Giả', 'sid-truyen-core' ); ?></label>
        <input type="text" id="sid_novel_author" name="sid_novel_author" value="<?php echo esc_attr( $author_name ); ?>" class="widefat">
    </p>
    <p>
        <label for="sid_novel_status"><?php _e( 'Trạng thái', 'sid-truyen-core' ); ?></label>
        <select id="sid_novel_status" name="sid_novel_status" class="widefat">
            <option value="ongoing" <?php selected( $status, 'ongoing' ); ?>><?php _e( 'Đang ra', 'sid-truyen-core' ); ?></option>
            <option value="completed" <?php selected( $status, 'completed' ); ?>><?php _e( 'Hoàn thành', 'sid-truyen-core' ); ?></option>
            <option value="paused" <?php selected( $status, 'paused' ); ?>><?php _e( 'Tạm dừng', 'sid-truyen-core' ); ?></option>
        </select>
    </p>
    <?php
}

// Chapter Parent Callback
function sid_chapter_parent_callback( $post ) {
    wp_nonce_field( 'sid_chapter_parent_save', 'sid_chapter_parent_nonce' );

    $parent_id = get_post_meta( $post->ID, '_sid_chapter_parent_novel', true );
    
    // Get all Novels
    $novels = get_posts(array(
        'post_type' => 'novel',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    ?>
    <p>
        <label for="sid_chapter_parent_novel"><?php _e( 'Chọn Truyện', 'sid-truyen-core' ); ?> <span class="required" style="color: red;">*</span></label>
        <select id="sid_chapter_parent_novel" name="sid_chapter_parent_novel" class="widefat" required>
            <option value=""><?php _e( 'Chọn một bộ truyện...', 'sid-truyen-core' ); ?></option>
            <?php foreach($novels as $novel): ?>
                <option value="<?php echo $novel->ID; ?>" <?php selected( $parent_id, $novel->ID ); ?>>
                    <?php echo esc_html($novel->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <!-- Simple numeric sort order field for chapters could be added here, currently using 'menu_order' or date -->
    <p class="description">Các chương được sắp xếp theo ngày đăng mặc định.</p>
    <?php
}

// Save Meta Data
function sid_core_save_meta_data( $post_id ) {
    // Check auto save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Save Novel Meta
    if ( isset( $_POST['sid_novel_info_nonce'] ) && wp_verify_nonce( $_POST['sid_novel_info_nonce'], 'sid_novel_info_save' ) ) {
        if ( isset( $_POST['sid_novel_author'] ) ) {
            update_post_meta( $post_id, '_sid_novel_author', sanitize_text_field( $_POST['sid_novel_author'] ) );
        }
        if ( isset( $_POST['sid_novel_status'] ) ) {
            update_post_meta( $post_id, '_sid_novel_status', sanitize_text_field( $_POST['sid_novel_status'] ) );
        }
    }

    // Save Chapter Meta
    if ( isset( $_POST['sid_chapter_parent_nonce'] ) && wp_verify_nonce( $_POST['sid_chapter_parent_nonce'], 'sid_chapter_parent_save' ) ) {
        // Validate required field
        if ( empty( $_POST['sid_chapter_parent_novel'] ) ) {
            // Show admin notice
            add_filter( 'redirect_post_location', function( $location ) {
                return add_query_arg( 'novel_required', '1', $location );
            } );
            // Set post to draft if trying to publish without novel
            if ( get_post_status( $post_id ) === 'publish' ) {
                wp_update_post( array(
                    'ID' => $post_id,
                    'post_status' => 'draft'
                ) );
            }
            return;
        }
        
        if ( isset( $_POST['sid_chapter_parent_novel'] ) ) {
            update_post_meta( $post_id, '_sid_chapter_parent_novel', sanitize_text_field( $_POST['sid_chapter_parent_novel'] ) );
        }
    }
}
add_action( 'save_post', 'sid_core_save_meta_data' );

// Chapter Cover Image Callback
function sid_chapter_cover_callback( $post ) {
    $parent_id = get_post_meta( $post->ID, '_sid_chapter_parent_novel', true );
    
    if ( ! $parent_id ) {
        ?>
        <p class="description" style="color: #999;">
            <?php _e( 'Vui lòng chọn truyện gốc để hiển thị ảnh bìa.', 'sid-truyen-core' ); ?>
        </p>
        <?php
        return;
    }
    
    // Get parent novel's cover image
    if ( has_post_thumbnail( $parent_id ) ) {
        $thumbnail_url = get_the_post_thumbnail_url( $parent_id, 'medium' );
        $novel_title = get_the_title( $parent_id );
        $novel_edit_link = get_edit_post_link( $parent_id );
        ?>
        <div style="text-align: center;">
            <img src="<?php echo esc_url( $thumbnail_url ); ?>" 
                 alt="<?php echo esc_attr( $novel_title ); ?>" 
                 style="max-width: 100%; height: auto; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <p style="margin-top: 10px; font-size: 13px;">
                <strong><?php echo esc_html( $novel_title ); ?></strong>
            </p>
            <p class="description">
                Ảnh bìa này sẽ được sử dụng làm thumbnail SEO cho chương.
                <br>
                <a href="<?php echo esc_url( $novel_edit_link ); ?>" target="_blank">Chỉnh sửa truyện gốc</a>
            </p>
        </div>
        <?php
    } else {
        $novel_title = get_the_title( $parent_id );
        $novel_edit_link = get_edit_post_link( $parent_id );
        ?>
        <p class="description" style="color: #d63638;">
            <?php _e( 'Truyện gốc chưa có ảnh bìa.', 'sid-truyen-core' ); ?>
            <br>
            <a href="<?php echo esc_url( $novel_edit_link ); ?>" target="_blank">Thêm ảnh bìa cho truyện</a>
        </p>
        <?php
    }
}

/**
 * Set Yoast SEO thumbnail for chapters to use parent novel's cover image
 */
function sid_core_set_chapter_yoast_image( $image ) {
    // Only apply to chapter post type
    if ( ! is_singular( 'chapter' ) ) {
        return $image;
    }
    
    $post_id = get_the_ID();
    $parent_id = get_post_meta( $post_id, '_sid_chapter_parent_novel', true );
    
    // If parent novel exists and has thumbnail, use it
    if ( $parent_id && has_post_thumbnail( $parent_id ) ) {
        $thumbnail_url = get_the_post_thumbnail_url( $parent_id, 'full' );
        if ( $thumbnail_url ) {
            return $thumbnail_url;
        }
    }
    
    return $image;
}
add_filter( 'wpseo_opengraph_image', 'sid_core_set_chapter_yoast_image', 10, 1 );
add_filter( 'wpseo_twitter_image', 'sid_core_set_chapter_yoast_image', 10, 1 );

/**
 * Set Yoast SEO image ID for chapters (for meta box display)
 */
function sid_core_set_chapter_yoast_image_id( $image_id ) {
    // Only apply to chapter post type in admin
    if ( ! is_admin() ) {
        return $image_id;
    }
    
    global $post;
    if ( ! $post || get_post_type( $post ) !== 'chapter' ) {
        return $image_id;
    }
    
    $parent_id = get_post_meta( $post->ID, '_sid_chapter_parent_novel', true );
    
    // If parent novel exists and has thumbnail, use it
    if ( $parent_id && has_post_thumbnail( $parent_id ) ) {
        $thumbnail_id = get_post_thumbnail_id( $parent_id );
        if ( $thumbnail_id ) {
            return $thumbnail_id;
        }
    }
    
    return $image_id;
}
add_filter( 'wpseo_opengraph_image_id', 'sid_core_set_chapter_yoast_image_id', 10, 1 );
add_filter( 'wpseo_twitter_image_id', 'sid_core_set_chapter_yoast_image_id', 10, 1 );
