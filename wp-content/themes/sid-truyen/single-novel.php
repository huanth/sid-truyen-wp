<?php get_header(); ?>

<main class="site-main py-8 bg-gray-50 dark:bg-dark-bg min-h-screen">
    <div class="container mx-auto px-4">
        
        <?php sid_truyen_breadcrumbs(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Novel Info (Sidebar on Desktop) -->
            <aside class="lg:col-span-1">
                <div class="bg-white dark:bg-dark-surface p-4 rounded-lg shadow-sm sticky top-24">
                    <!-- Cover Image -->
                    <div class="aspect-[2/3] bg-gray-200 dark:bg-gray-700 rounded mb-4 flex items-center justify-center relative overflow-hidden">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                        <?php else : ?>
                            <div class="text-4xl text-gray-400 font-bold select-none">
                                <?php echo substr( get_the_title(), 0, 1 ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 text-center">
                        <?php the_title(); ?>
                    </h1>
                    
                    <?php 
                    $author_name = get_post_meta( get_the_ID(), '_sid_novel_author', true ); 
                    if ( $author_name ) :
                    ?>
                    <div class="text-sm text-center text-gray-500 dark:text-gray-400 mb-4">
                        <?php echo sprintf( __( 'Tác giả: %s', 'sid-truyen' ), esc_html( $author_name ) ); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php
                    $status = get_post_meta( get_the_ID(), '_sid_novel_status', true );
                    $status_label = ucfirst($status);
                    if($status === 'ongoing') $status_label = 'Đang ra';
                    if($status === 'completed') $status_label = 'Hoàn thành';
                    if($status === 'paused') $status_label = 'Tạm dừng';
                    
                    $status_class = 'bg-gray-100 text-gray-700';
                    if($status === 'ongoing') $status_class = 'bg-green-100 text-green-700';
                    if($status === 'completed') $status_class = 'bg-blue-100 text-blue-700';
                    if($status === 'paused') $status_class = 'bg-red-100 text-red-700';
                    ?>
                    <div class="flex justify-center space-x-2 mb-4">
                         <span class="px-2 py-1 text-xs rounded-full <?php echo $status_class; ?>">
                             <?php echo $status_label ? esc_html($status_label) : 'Chưa rõ'; ?>
                         </span>
                    </div>

                    <!-- Genres -->
                    <?php
                    $genres = get_the_terms( get_the_ID(), 'genre' );
                    if ( $genres && ! is_wp_error( $genres ) ) :
                    ?>
                    <div class="flex flex-wrap justify-center gap-2 mb-4">
                        <?php foreach ( $genres as $genre ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $genre ) ); ?>" class="px-2 py-1 text-xs font-bold text-primary bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-md hover:bg-primary hover:text-white transition-all">
                                <?php echo esc_html( $genre->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php endif; ?>

                    <!-- Ebook Download Button -->
                    <div class="mb-6 group">
                        <a href="<?php echo add_query_arg(['ebook_download' => '1', 'post_id' => get_the_ID()], home_url()); ?>" class="relative flex items-center justify-between w-full p-4 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 text-white rounded-xl shadow-lg border border-gray-700 hover:border-emerald-500 hover:shadow-emerald-500/20 transition-all duration-300 group-hover:-translate-y-1 overflow-hidden">
                            <!-- Background Glow -->
                            <div class="absolute inset-0 bg-emerald-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <div class="z-10 flex flex-col items-start">
                                <span class="text-xs text-emerald-400 font-bold uppercase tracking-wider mb-1">Ebook Full</span>
                                <span class="font-bold text-lg leading-none">Tải Xuống Ngay</span>
                            </div>
                            
                            <div class="z-10 w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </div>
                        </a>
                        <div class="mt-2 flex items-center justify-center text-[10px] text-gray-500 dark:text-gray-400 space-x-2">
                            <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Định dạng .txt</span>
                            <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Cập nhật 24/7</span>
                        </div>
                    </div>


                </div>
            </aside>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Description Section -->
                <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm">
                   <h2 class="text-xl font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
                        Giới Thiệu
                    </h2>
                    <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4 font-reading">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Chapter List Section -->
                <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-200 dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
                        Danh Sách Chương
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
<?php
                        $novel_id = get_the_ID();
                        // Prioritize 'chapters_page' query parameter for robust pagination
                        // We use 'chapters_page' instead of 'page' because 'page' is a reserved WP public var for single post content pagination
                        $paged = isset( $_GET['chapters_page'] ) ? absint( $_GET['chapters_page'] ) : 1;
                        
                        // Fallback to standard WP query vars if custom param is missing but we're in a rewrite context
                        if ( $paged <= 1 ) {
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
                        }
                        
                        // Query Chapters linked to this novel
                        $args = array(
                            'post_type' => 'chapter',
                            'posts_per_page' => 20,
                            'paged' => $paged,
                            'meta_key' => '_sid_chapter_parent_novel',
                            'meta_value' => $novel_id,
                            'orderby' => 'date',
                            'order' => 'DESC' // Newest first
                        );
                        $chapter_query = new WP_Query($args);

                        if ($chapter_query->have_posts()) :
                            while ($chapter_query->have_posts()) : $chapter_query->the_post();
                                // Check if chapter is new (posted within 24 hours)
                                $post_time = get_the_time('U');
                                $current_time = current_time('timestamp');
                                $time_diff = $current_time - $post_time;
                                $is_new = ($time_diff < 86400); // 86400 seconds = 24 hours
                                ?>
                                <a href="<?php the_permalink(); ?>" class="text-sm text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded transition-colors block group">
                                    <span class="flex items-center gap-2">
                                        <span class="truncate"><?php the_title(); ?></span>
                                        <?php if ($is_new) : ?>
                                            <span style="padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: bold; background: linear-gradient(to right, #ef4444, #f97316); color: white; border-radius: 9999px; text-transform: uppercase; flex-shrink: 0; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">NEW</span>
                                        <?php endif; ?>
                                    </span>
                                </a>
                                <?php
                            endwhile;
                            
                            // Pagination
                            if ($chapter_query->max_num_pages > 1) :
                                ?>
                                <div class="col-span-full mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-center items-center space-x-2">
                                        <?php
                                        $pagination = paginate_links(array(
                                            'base' => add_query_arg( 'chapters_page', '%#%' ),
                                            'format' => '',
                                            'current' => $paged,
                                            'total' => $chapter_query->max_num_pages,
                                            'prev_text' => '&laquo; Trước',
                                            'next_text' => 'Sau &raquo;',
                                            'type' => 'array',
                                            'before_page_number' => '<span class="px-3 py-2 text-sm">',
                                            'after_page_number' => '</span>'
                                        ));
                                        
                                        if ($pagination) {
                                            foreach ($pagination as $page) {
                                                // Style current page differently
                                                if (strpos($page, 'current') !== false) {
                                                    echo str_replace('page-numbers current', 'page-numbers current bg-primary text-white font-bold rounded', $page);
                                                } else {
                                                    echo str_replace('page-numbers', 'page-numbers text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors', $page);
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                            endif;
                            
                            wp_reset_postdata();
                        else :
                            echo '<p class="text-gray-500">Chưa có chương nào.</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>
