<?php get_header(); ?>

<main class="site-main bg-[#f9f9f9] dark:bg-[#1a1a1a] min-h-screen pb-12">
    
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <?php sid_truyen_breadcrumbs(); ?>
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php
            // Get Parent Novel Info
            $parent_novel_id = get_post_meta( get_the_ID(), '_sid_chapter_parent_novel', true );
            $novel_permalink = $parent_novel_id ? get_permalink( $parent_novel_id ) : home_url();
            $novel_title     = $parent_novel_id ? get_the_title( $parent_novel_id ) : '';
            
            // Logic for Next/Prev Chapter within the same Novel
            $next_chapter_link = '#';
            $prev_chapter_link = '#';
            $next_chapter_class = 'opacity-50 cursor-not-allowed';
            $prev_chapter_class = 'opacity-50 cursor-not-allowed';

            if ( $parent_novel_id ) {
                // Get all chapters of this novel
                $all_chapters = get_posts( array(
                    'post_type'      => 'chapter',
                    'posts_per_page' => -1,
                    'meta_key'       => '_sid_chapter_parent_novel',
                    'meta_value'     => $parent_novel_id,
                    'orderby'        => 'date',
                    'order'          => 'ASC',
                    'fields'         => 'ids',
                ) );

                $current_index = array_search( get_the_ID(), $all_chapters );

                if ( $current_index !== false ) {
                    // Previous
                    if ( isset( $all_chapters[ $current_index - 1 ] ) ) {
                        $prev_chapter_link  = get_permalink( $all_chapters[ $current_index - 1 ] );
                        $prev_chapter_class = 'hover:bg-primary hover:text-white';
                    }
                    // Next
                    if ( isset( $all_chapters[ $current_index + 1 ] ) ) {
                        $next_chapter_link  = get_permalink( $all_chapters[ $current_index + 1 ] );
                        $next_chapter_class = 'hover:bg-primary hover:text-white';
                    }
                }
            }
            ?>

            <article id="post-<?php the_ID(); ?>" class="bg-white dark:bg-dark-surface shadow-sm rounded-lg p-6 md:p-10">
                
                <header class="mb-8 text-center border-b border-gray-100 dark:border-gray-700 pb-6">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4 font-reading leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    <div class="text-sm text-gray-500 dark:text-gray-400 space-x-2">
                         <span><i class="icon-calendar"></i> <?php echo get_the_date(); ?></span>
                         <?php if ( $novel_title ): ?>
                             <span>&bull;</span>
                             <a href="<?php echo esc_url( $novel_permalink ); ?>" class="text-primary hover:underline"><?php echo esc_html( $novel_title ); ?></a>
                         <?php endif; ?>
                    </div>
                </header>
                
                <!-- Controls (Font size, etc) -->
                <div class="flex justify-center space-x-4 mb-8 text-sm text-gray-600 dark:text-gray-400">
                    <button id="font-decrease" class="hover:text-primary transition-colors px-2 py-1 border border-gray-200 dark:border-gray-700 rounded">A-</button>
                    <button id="font-reset" class="hover:text-primary transition-colors px-2 py-1 border border-gray-200 dark:border-gray-700 rounded">A</button>
                    <button id="font-increase" class="hover:text-primary transition-colors px-2 py-1 border border-gray-200 dark:border-gray-700 rounded">A+</button>
                </div>

                <!-- Navigation Top -->
                <div class="flex justify-between items-center mb-8">
                    <a href="<?php echo $prev_chapter_link; ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded transition-colors <?php echo $prev_chapter_class; ?>">
                        &larr; Trước
                    </a>

                    <a href="<?php echo esc_url( $novel_permalink ); ?>" class="text-gray-500 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </a>

                    <a href="<?php echo $next_chapter_link; ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded transition-colors <?php echo $next_chapter_class; ?>">
                        Sau &rarr;
                    </a>
                </div>

                <!-- Content -->
                <div class="chapter-content prose dark:prose-invert max-w-none text-lg leading-loose font-reading text-gray-800 dark:text-gray-300">
                    <?php the_content(); ?>
                </div>

                <!-- Navigation Bottom -->
                <div class="flex justify-between items-center mt-12 pt-8 border-t border-gray-100 dark:border-gray-700">
                     <a href="<?php echo $prev_chapter_link; ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded transition-colors <?php echo $prev_chapter_class; ?>">
                        &larr; Trước
                    </a>

                    <a href="<?php echo $next_chapter_link; ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded transition-colors <?php echo $next_chapter_class; ?>">
                        Sau &rarr;
                    </a>
                </div>

            </article>
        <?php endwhile; endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
