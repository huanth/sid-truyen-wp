<?php get_header(); ?>

<main class="site-main bg-gray-50 dark:bg-dark-bg min-h-screen py-12 transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" class="bg-white dark:bg-dark-surface p-8 md:p-12 rounded-xl shadow-sm">
                
                <header class="mb-8 border-b border-gray-100 dark:border-gray-700 pb-6">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 font-reading">
                        <?php the_title(); ?>
                    </h1>
                </header>

                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed font-reading">
                    <?php the_content(); ?>
                </div>

            </article>
        <?php endwhile; endif; ?>

    </div>
</main>

<?php get_footer(); ?>
