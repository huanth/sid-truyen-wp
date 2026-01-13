<?php
get_header(); ?>

<main class="container mx-auto px-4 py-8">
	<?php if ( have_posts() ) : ?>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-surface rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden'); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="block h-48 overflow-hidden">
							<?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105']); ?>
						</a>
					<?php endif; ?>
					
					<div class="p-4">
						<header class="mb-2">
							<h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
								<a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
									<?php the_title(); ?>
								</a>
							</h2>
							<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
								<?php echo get_the_date(); ?>
							</div>
						</header>

						<div class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
							<?php the_excerpt(); ?>
						</div>

						<footer class="flex justify-between items-center mt-auto">
							<a href="<?php the_permalink(); ?>" class="text-sm font-semibold text-primary hover:text-secondary transition-colors">
								Read more &rarr;
							</a>
						</footer>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="mt-8">
			<?php
			the_posts_pagination( array(
				'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page', 'sid-truyen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'sid-truyen' ) . '</span>',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'sid-truyen' ) . ' </span>',
			) );
			?>
		</div>

	<?php    else :
        ?>
        <div class="col-span-full text-center py-20">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-4"><?php _e( 'Không tìm thấy nội dung', 'sid-truyen' ); ?></h2>
            <p class="text-gray-500 dark:text-gray-400"><?php _e( 'Chưa có bài viết nào được đăng tải.', 'sid-truyen' ); ?></p>
        </div>
        <?php
    endif;
    ?>
</main>

<?php
get_footer();
