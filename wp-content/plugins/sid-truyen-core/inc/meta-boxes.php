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
