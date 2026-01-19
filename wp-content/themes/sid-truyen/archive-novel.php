<?php get_header(); ?>

<main class="site-main py-8 bg-gray-50 dark:bg-dark-bg min-h-screen">
    <div class="container mx-auto px-4">
        
        <?php sid_truyen_breadcrumbs(); ?>
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                <?php
                $page_title = 'Tất cả truyện';
                if ( get_query_var( 'v_sort' ) === 'views' ) {
                    $page_title = 'Truyện Hot 🔥';
                } elseif ( get_query_var( 'v_status' ) === 'completed' ) {
                    $page_title = 'Truyện đã hoàn thành';
                }
                echo esc_html( $page_title );
                ?>
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Khám phá kho tàng truyện phong phú với nhiều thể loại đa dạng.
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-dark-surface p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-800 mb-8 max-w-full">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                <!-- Genre Filter -->
                <div class="w-full md:w-auto relative group">
                    <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none text-gray-500 z-10" style="width: 3rem;">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <select onchange="window.location.href=this.value" class="w-full md:w-64 appearance-none pl-12 pr-10 py-2.5 bg-gray-50 dark:bg-dark-bg border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow cursor-pointer relative z-0 leading-tight" style="padding-left: 3rem;">
                        <option value="<?php echo get_post_type_archive_link('novel'); ?>">Tất cả thể loại</option>
                        <?php
                        $genres = get_terms(array(
                            'taxonomy' => 'genre',
                            'hide_empty' => true,
                        ));
                        if (!empty($genres) && !is_wp_error($genres)) {
                            foreach ($genres as $genre) {
                                $selected = (is_tax('genre') && get_queried_object_id() === $genre->term_id) ? 'selected' : '';
                                echo '<option value="' . esc_url(get_term_link($genre)) . '" ' . $selected . '>' . esc_html($genre->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <div class="absolute inset-y-0 right-0 w-10 flex items-center justify-center pointer-events-none text-gray-500 z-10">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 items-center w-full md:w-auto justify-end">
                    <!-- Status Filter -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase text-gray-400 hidden sm:inline-block">Trạng thái:</span>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'novel' ) ); ?>" class="<?php echo ( ! get_query_var('v_status') ) ? 'bg-primary text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'; ?> px-3 py-1.5 rounded text-xs font-medium transition-all">Tất cả</a>
                        <a href="<?php echo esc_url( home_url( '/truyen-hoan-thanh/' ) ); ?>" class="<?php echo ( get_query_var('v_status') === 'completed' ) ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'; ?> px-3 py-1.5 rounded text-xs font-medium transition-all">Đã hoàn thành</a>
                    </div>

                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-700 hidden md:block"></div>

                    <!-- Sort Filter -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase text-gray-400 hidden sm:inline-block">Sắp xếp:</span>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'novel' ) ); ?>" class="<?php echo ( ! get_query_var('v_sort') ) ? 'text-primary font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-primary'; ?> text-sm transition-colors">Mới nhất</a>
                        <a href="<?php echo esc_url( home_url( '/truyen-hot/' ) ); ?>" class="<?php echo ( get_query_var('v_sort') === 'views' ) ? 'text-primary font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-primary'; ?> text-sm transition-colors">Xem nhiều</a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        
        if ( have_posts() ) : ?>
            
            <!-- Novels Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-12">
                <?php while ( have_posts() ) : the_post(); ?>
                    
                    <a href="<?php the_permalink(); ?>" class="group">
                        <div class="aspect-[2/3] bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden relative shadow-sm group-hover:shadow-lg transition-all duration-300">
                             <?php if(has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover']); ?>
                             <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-gray-500 font-bold text-4xl select-none">
                                    <?php echo substr(get_the_title(), 0, 1); ?>
                                </div>
                             <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Status Badge -->
                            <?php
                            // Status Badge with Hot priority based on views
                            $views = (int) get_post_meta(get_the_ID(), '_sid_novel_views', true);
                            $status = get_post_meta(get_the_ID(), '_sid_novel_status', true);
                            
                            if ($views >= 1000) : ?>
                                <span class="absolute top-2 right-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-[10px] px-2 py-1 rounded shadow-lg font-bold uppercase tracking-wider">🔥 Hot</span>
                            <?php elseif ($status === 'completed') : ?>
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-[10px] px-2 py-1 rounded shadow-lg font-bold uppercase tracking-wider">Hoàn thành</span>
                            <?php elseif ($status === 'ongoing') : ?>
                                <span class="absolute top-2 right-2 bg-blue-500 text-white text-[10px] px-2 py-1 rounded shadow-lg font-bold uppercase tracking-wider">Đang ra</span>
                            <?php elseif ($status === 'paused') : ?>
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-[10px] px-2 py-1 rounded shadow-lg font-bold uppercase tracking-wider">Tạm dừng</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary transition-colors line-clamp-2">
                            <?php the_title(); ?>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <?php 
                            $chapter_count = get_posts(array(
                                'post_type' => 'chapter',
                                'meta_key' => '_sid_chapter_parent_novel',
                                'meta_value' => get_the_ID(),
                                'posts_per_page' => -1,
                                'fields' => 'ids'
                            ));
                            echo count($chapter_count) . ' Chương'; 
                            ?>
                        </p>
                    </a>
                    
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <?php
            // Use built-in pagination
            $pagination = paginate_links(array(
                'prev_text' => '← Trước',
                'next_text' => 'Sau →',
                'type' => 'array',
                'mid_size' => 2
            ));
            
            if ($pagination) : ?>
                <nav class="flex justify-center items-center flex-wrap gap-2 mt-12">
                    <?php foreach ($pagination as $page) : ?>
                        <?php
                        // Style the pagination links
                        if (strpos($page, 'current') !== false) {
                            // Current page
                            $page = str_replace('page-numbers', 'px-4 py-2 bg-primary text-white rounded font-medium', $page);
                        } else {
                            // Other pages
                            $page = str_replace('page-numbers', 'px-4 py-2 border border-gray-300 dark:border-gray-600 rounded hover:bg-primary hover:text-white hover:border-primary transition-colors', $page);
                        }
                        echo $page;
                        ?>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
            
        <?php else : ?>
            
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Chưa có truyện nào</h2>
                <p class="text-gray-500 dark:text-gray-400">Thể loại này chưa có truyện. Vui lòng quay lại sau.</p>
            </div>
            
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
