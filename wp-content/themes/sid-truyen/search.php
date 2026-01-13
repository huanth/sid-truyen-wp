<?php get_header(); ?>

<main class="site-main py-8 bg-gray-50 dark:bg-dark-bg min-h-screen">
    <div class="container mx-auto px-4">
        
        <?php sid_truyen_breadcrumbs(); ?>
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                <?php printf( __( 'Kết quả tìm kiếm: %s', 'sid-truyen' ), '<span class="text-primary">' . get_search_query() . '</span>' ); ?>
            </h1>
            <?php if ( have_posts() ) : ?>
                <p class="text-gray-600 dark:text-gray-400">
                    <?php printf( __( 'Tìm thấy %s truyện', 'sid-truyen' ), $wp_query->found_posts ); ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
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
                                'fields' => 'ids'
                            ));
                            echo count($chapter_count) . ' Chương'; 
                            ?>
                        </p>
                    </a>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '&larr; Trước', 'sid-truyen' ),
                    'next_text' => __( 'Sau &rarr;', 'sid-truyen' ),
                    'class'     => 'flex justify-center space-x-2',
                ) );
                ?>
            </div>

        <?php else : ?>
            <div class="bg-white dark:bg-dark-surface rounded-lg shadow-sm p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Không tìm thấy truyện</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Thử lại với từ khóa khác hoặc <a href="<?php echo home_url(); ?>" class="text-primary hover:underline">quay về trang chủ</a></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
