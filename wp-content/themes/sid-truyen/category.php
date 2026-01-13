<?php get_header(); ?>

<main class="site-main py-8 bg-gray-50 dark:bg-dark-bg min-h-screen">
    <div class="container mx-auto px-4">
        
        <?php sid_truyen_breadcrumbs(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Novel Info (Sidebar on Desktop) -->
            <aside class="lg:col-span-1">
                <div class="bg-white dark:bg-dark-surface p-4 rounded-lg shadow-sm sticky top-24">
                    <!-- Placeholder Cover -->
                    <div class="aspect-[2/3] bg-gray-200 dark:bg-gray-700 rounded mb-4 flex items-center justify-center text-4xl text-gray-400 font-bold select-none relative overflow-hidden">
                        <?php echo substr(single_cat_title('', false), 0, 1); ?>
                         <!-- Real implementation would fetch image here -->
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 text-center">
                        <?php single_cat_title(); ?>
                    </h1>
                    
                    <div class="text-sm text-center text-gray-500 dark:text-gray-400 mb-4">
                        <?php echo _e('Author: Unknown', 'sid-truyen'); // Placeholder ?>
                    </div>
                    
                    <div class="flex justify-center space-x-2 mb-4">
                         <!-- Example status tags -->
                         <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Ongoing</span>
                         <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Novel</span>
                    </div>

                    <div class="prose dark:prose-invert text-sm text-gray-600 dark:text-gray-300 mb-4">
                        <?php echo category_description(); ?>
                    </div>
                </div>
            </aside>

            <!-- Chapter List (Main Content) -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-200 dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
                        Chapter List
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                        <?php
                        // Custom query to get posts in ASC order (Chapter 1 to Last)
                        // Use the current category
                        $current_cat_id = get_queried_object_id();
                        $args = array(
                            'cat' => $current_cat_id,
                            'order' => 'ASC',
                            'orderby' => 'date',
                            'posts_per_page' => -1 // Show all chapters
                        );
                        $chapter_query = new WP_Query($args);

                        if ($chapter_query->have_posts()) :
                            while ($chapter_query->have_posts()) : $chapter_query->the_post();
                                ?>
                                <a href="<?php the_permalink(); ?>" class="text-sm text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded transition-colors truncate block">
                                    <?php the_title(); ?>
                                </a>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p class="text-gray-500">No chapters found.</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php get_footer(); ?>
