<?php get_header(); ?>

<main class="site-main">
    
    <!-- Hero / Featured Section -->
    <section class="bg-gray-50 dark:bg-dark-surface py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 border-l-4 border-primary pl-4 text-gray-800 dark:text-gray-100">Truyện Hot</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php
                // Query for "Hot" novels (top by views)
                $args = array(
                    'post_type' => 'novel',
                    'posts_per_page' => 12,
                    'meta_key' => '_sid_novel_views',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC'
                );
                $novels_query = new WP_Query($args);
                
                if ($novels_query->have_posts()) :
                    while ($novels_query->have_posts()) : $novels_query->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="group">
                        <div class="aspect-[2/3] bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden relative shadow-sm group-hover:shadow-lg transition-all duration-300">
                             <?php if(has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover', 'alt' => get_the_title()]); ?>
                             <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-gray-500 font-bold text-4xl select-none">
                                    <?php echo substr(get_the_title(), 0, 1); ?>
                                </div>
                             <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary transition-colors line-clamp-2">
                            <?php the_title(); ?>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <?php 
                            // Count chapters linked to this novel
                            $chapter_count = get_posts(array(
                                'post_type' => 'chapter',
                                'meta_key' => '_sid_chapter_parent_novel',
                                'meta_value' => get_the_ID(),
                                'posts_per_page' => -1,
                                'fields' => 'ids' // only need count
                            ));
                            echo count($chapter_count) . ' Chương'; 
                            ?>
                        </p>
                    </a>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <div class="container mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Latest Updates (Left Column) -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold border-l-4 border-secondary pl-4 text-gray-800 dark:text-gray-100">Mới Cập Nhật</h2>
                <a href="<?php echo get_post_type_archive_link('novel'); ?>" class="text-sm text-primary hover:underline">Xem tất cả</a>
            </div>

            <div class="space-y-4">
                <?php
                // Latest Chapters
                $latest_args = array(
                    'post_type' => 'chapter',
                    'posts_per_page' => 20,
                );
                $latest_query = new WP_Query($latest_args);

                if ($latest_query->have_posts()) :
                    while ($latest_query->have_posts()) : $latest_query->the_post();
                        $parent_id = get_post_meta(get_the_ID(), '_sid_chapter_parent_novel', true);
                        $novel_title = $parent_id ? get_the_title($parent_id) : 'Unknown Novel';
                        $novel_link = $parent_id ? get_permalink($parent_id) : '#';
                        ?>
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 hover:bg-gray-50 dark:hover:bg-dark-surface/50 p-2 rounded transition-colors">
                            <div class="flex items-center space-x-4 overflow-hidden">
                                <a href="<?php echo $novel_link; ?>" class="text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-primary truncate max-w-[150px] md:max-w-xs">
                                    <?php echo $novel_title; ?>
                                </a>
                                <span class="text-gray-400">/</span>
                                <a href="<?php the_permalink(); ?>" class="text-sm text-gray-600 dark:text-gray-300 hover:text-secondary truncate">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                            <div class="flex-shrink-0 text-xs text-gray-400 italic ml-4">
                                <?php echo sid_truyen_time_ago(get_the_time('U')); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>Không có truyện nào.</p>';
                endif;
                ?>
            </div>


        </div>

        <!-- Sidebar (Right Column) -->
        <aside class="space-y-8">
            <!-- Top Reads -->
            <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Đọc Nhiều</h3>
                <ul class="space-y-3">
                    <?php
                    // Top Read Novels (by views)
                    $top_args = array(
                       'post_type' => 'novel',
                       'meta_key' => '_sid_novel_views',
                       'orderby' => 'meta_value_num',
                       'order' => 'DESC',
                       'posts_per_page' => 5
                    );
                    $top_query = new WP_Query($top_args);
                    $rank = 1;
                    if ($top_query->have_posts()) :
                        while ($top_query->have_posts()) : $top_query->the_post();
                        $terms = get_the_terms(get_the_ID(), 'genre');
                    ?>
                    <li class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-bold <?php echo $rank <= 3 ? 'text-primary' : 'text-gray-500'; ?>">
                            <?php echo $rank++; ?>
                        </span>
                        <div>
                            <a href="<?php the_permalink(); ?>" class="text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary line-clamp-2">
                                <?php the_title(); ?>
                            </a>
                            <div class="text-xs text-gray-400 mt-1">
                                <?php echo !empty($terms) ? $terms[0]->name : 'Truyện'; ?>
                            </div>
                        </div>
                    </li>
                    <?php
                        endwhile; 
                        wp_reset_postdata(); 
                    endif;
                    ?>
                </ul>
            </div>
            
            <!-- Genres/Categories Tag Cloud -->
            <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Thể Loại</h3>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $genres = get_terms(array(
                        'taxonomy' => 'genre',
                        'hide_empty' => false,
                        'number' => 20
                    ));
                    if(!empty($genres) && !is_wp_error($genres)):
                        foreach($genres as $genre): ?>
                            <a href="<?php echo get_term_link($genre); ?>" class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-xs text-gray-600 dark:text-gray-300 rounded-full hover:bg-primary hover:text-white transition-colors">
                                <?php echo $genre->name; ?>
                            </a>
                        <?php endforeach;
                    else: ?>
                        <span class="text-xs text-gray-400">Chưa có thể loại.</span>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

    </div>

    <!-- Completed Stories Section (Full Width) -->
    <section class="bg-gray-50 dark:bg-dark-surface py-8 border-t border-gray-100 dark:border-gray-800">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold border-l-4 border-success pl-4 text-gray-800 dark:text-gray-100">Truyện Đã Hoàn Thành</h2>
                <a href="<?php echo esc_url( home_url( '/truyen-hoan-thanh/' ) ); ?>" class="text-sm text-primary hover:underline">Xem tất cả</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php
                // Query for "Completed" novels
                $completed_args = array(
                    'post_type' => 'novel',
                    'posts_per_page' => 12,
                    'meta_key' => '_sid_novel_status',
                    'meta_value' => 'completed'
                );
                $completed_query = new WP_Query($completed_args);
                
                if ($completed_query->have_posts()) :
                    while ($completed_query->have_posts()) : $completed_query->the_post();
                    ?>
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
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary transition-colors line-clamp-2">
                            <?php the_title(); ?>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <?php 
                            // Count chapters linked to this novel
                            $chapter_count = get_posts(array(
                                'post_type' => 'chapter',
                                'meta_key' => '_sid_chapter_parent_novel',
                                'meta_value' => get_the_ID(),
                                'posts_per_page' => -1,
                                'fields' => 'ids' // only need count
                            ));
                            echo count($chapter_count) . ' Chương'; 
                            ?>
                        </p>
                    </a>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else: 
                     echo '<p class="col-span-full text-center text-gray-500 text-sm">Chưa có truyện nào hoàn thành.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
