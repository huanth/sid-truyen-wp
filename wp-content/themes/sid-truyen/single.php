<?php get_header(); ?>

<main class="site-main bg-[#f9f9f9] dark:bg-[#1a1a1a] min-h-screen pb-12">
    
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <?php sid_truyen_breadcrumbs(); ?>
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" class="bg-white dark:bg-dark-surface shadow-sm rounded-lg p-6 md:p-10">
                
                <header class="mb-8 text-center border-b border-gray-100 dark:border-gray-700 pb-6">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4 font-reading leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    <div class="text-sm text-gray-500 dark:text-gray-400 space-x-2">
                         <span><i class="icon-calendar"></i> <?php echo get_the_date(); ?></span>
                         <span>&bull;</span>
                         <?php 
                         $cat = get_the_category(); 
                         if($cat) {
                             echo '<a href="' . get_category_link($cat[0]->term_id) . '" class="text-primary hover:underline">' . $cat[0]->name . '</a>';
                         }
                         ?>
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
                    <?php 
                    $prev_post = get_previous_post(true);
                    $next_post = get_next_post(true);
                    ?>
                    <?php if($prev_post): ?>
                        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-primary hover:text-white transition-colors">
                            &larr; Prev
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-gray-300 dark:text-gray-600 cursor-not-allowed">&larr; Prev</span>
                    <?php endif; ?>

                    <a href="<?php echo $cat ? get_category_link($cat[0]->term_id) : home_url(); ?>" class="text-gray-500 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </a>

                    <?php if($next_post): ?>
                        <a href="<?php echo get_permalink($next_post->ID); ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-primary hover:text-white transition-colors">
                            Next &rarr;
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-gray-300 dark:text-gray-600 cursor-not-allowed">Next &rarr;</span>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="prose dark:prose-invert max-w-none text-lg leading-loose font-reading text-gray-800 dark:text-gray-300">
                    <?php the_content(); ?>
                </div>

                <!-- Navigation Bottom -->
                <div class="flex justify-between items-center mt-12 pt-8 border-t border-gray-100 dark:border-gray-700">
                     <?php if($prev_post): ?>
                        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-primary hover:text-white transition-colors">
                            &larr; Prev
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-gray-300 dark:text-gray-600 cursor-not-allowed">&larr; Prev</span>
                    <?php endif; ?>

                    <?php if($next_post): ?>
                        <a href="<?php echo get_permalink($next_post->ID); ?>" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-primary hover:text-white transition-colors">
                            Next &rarr;
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-gray-300 dark:text-gray-600 cursor-not-allowed">Next &rarr;</span>
                    <?php endif; ?>
                </div>

            </article>
        <?php endwhile; endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
