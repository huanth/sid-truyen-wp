<?php get_header(); ?>

<!-- Reading Progress Bar -->
<div class="fixed top-0 left-0 w-full h-1 z-[60] pointer-events-none">
    <div id="reading-progress" class="h-full bg-primary origin-left scale-x-0 transition-transform duration-100 ease-out"></div>
</div>

<main class="site-main bg-[#faf9f6] dark:bg-[#121212] min-h-screen pb-20 transition-colors duration-300">
    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php
        $parent_novel_id = get_post_meta( get_the_ID(), '_sid_chapter_parent_novel', true );
        $novel_permalink = $parent_novel_id ? get_permalink( $parent_novel_id ) : home_url();
        $novel_title     = $parent_novel_id ? get_the_title( $parent_novel_id ) : 'Trang chủ';
        
        // Navigation Logic
        $next_chapter_link = '#';
        $prev_chapter_link = '#';
        $next_chapter_class = 'opacity-50 cursor-not-allowed pointer-events-none';
        $prev_chapter_class = 'opacity-50 cursor-not-allowed pointer-events-none';

        if ( $parent_novel_id ) {
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
                if ( isset( $all_chapters[ $current_index - 1 ] ) ) {
                    $prev_chapter_link  = get_permalink( $all_chapters[ $current_index - 1 ] );
                    $prev_chapter_class = 'hover:bg-primary hover:text-white shadow-sm hover:shadow-md';
                }
                if ( isset( $all_chapters[ $current_index + 1 ] ) ) {
                    $next_chapter_link  = get_permalink( $all_chapters[ $current_index + 1 ] );
                    $next_chapter_class = 'hover:bg-primary hover:text-white shadow-sm hover:shadow-md';
                }
            }
        }
        ?>

        <!-- Sticky Toolbar -->
        <div id="sticky-toolbar" class="sticky top-0 z-50 bg-white/90 dark:bg-[#1a1a1a]/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm transition-transform duration-300">
            <div class="container mx-auto px-4 h-16 flex items-center justify-between">
                
                <!-- Left: Back Button & Title -->
                <div class="flex items-center space-x-4 overflow-hidden">
                    <a href="<?php echo esc_url($novel_permalink); ?>" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors" title="Quay lại truyện">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div class="flex flex-col overflow-hidden">
                        <a href="<?php echo esc_url($novel_permalink); ?>" class="text-xs text-gray-500 dark:text-gray-400 truncate hover:text-primary transition-colors">
                            <?php echo esc_html($novel_title); ?>
                        </a>
                    </div>
                </div>

                <!-- Right: Controls -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <!-- Font Controls -->
                    <div class="flex border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                        <button id="font-decrease" class="px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700 text-sm">A-</button>
                        <button id="font-reset" class="px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700 text-sm">A</button>
                        <button id="font-increase" class="px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm">A+</button>
                    </div>

                    <!-- Download Button (Icon Only on Mobile) -->
                    <a href="<?php echo add_query_arg(['ebook_download' => '1', 'post_id' => get_the_ID()], home_url()); ?>" class="hidden md:flex items-center px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold shadow-sm shadow-emerald-500/30 transition-all hover:-translate-y-0.5" title="Tải chương">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Tải
                    </a>
                    <a href="<?php echo add_query_arg(['ebook_download' => '1', 'post_id' => get_the_ID()], home_url()); ?>" class="md:hidden w-9 h-9 flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-sm shadow-emerald-500/30">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumbs (Simplified) -->
            <div class="flex justify-center mb-10 opacity-70">
                <?php sid_truyen_breadcrumbs(); ?>
            </div>

            <!-- Top Navigation Buttons -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    
                    <a href="<?php echo $prev_chapter_link; ?>" class="w-full md:w-auto px-6 py-4 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 transition-all group <?php echo $prev_chapter_class; ?>">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span class="font-bold">Chương Trước</span>
                    </a>

                    <a href="<?php echo esc_url($novel_permalink); ?>" class="hidden md:flex flex-col items-center justify-center text-gray-400 hover:text-primary transition-colors">
                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="text-xs font-bold uppercase tracking-wider">Danh Sách</span>
                    </a>

                    <a href="<?php echo $next_chapter_link; ?>" class="w-full md:w-auto px-6 py-4 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 transition-all group <?php echo $next_chapter_class; ?>">
                        <span class="font-bold">Chương Sau</span>
                        <svg class="w-5 h-5 ml-3 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                </div>
                
                <!-- Mobile List Button -->
                <div class="md:hidden mt-4">
                    <a href="<?php echo esc_url($novel_permalink); ?>" class="block w-full text-center py-3 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400 text-sm font-bold">
                        Danh Sách Chương
                    </a>
                </div>
            </div>

            <!-- Chapter Content -->
            <article id="post-<?php the_ID(); ?>" class="chapter-content-wrapper">
                
                <header class="text-center mb-12">
                    <span class="text-xs font-bold tracking-widest text-gray-400 uppercase mb-6 block"><?php echo get_the_date(); ?></span>
                    <h1 class="text-3xl md:text-5xl font-bold text-gray-800 dark:text-gray-100 font-sans leading-tight mb-4">
                        <?php the_title(); ?>
                    </h1>
                     <div class="w-20 h-1 bg-primary mx-auto rounded-full"></div>
                </header>

                <div class="chapter-content prose dark:prose-invert max-w-none text-xl leading-loose font-sans text-gray-800 dark:text-gray-300 md:px-0 selection:bg-primary/20">
                    <?php the_content(); ?>
                </div>

                <!-- Footer Navigation -->
                <div class="border-t border-gray-200 dark:border-gray-800 mt-16 pt-10">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        
                        <a href="<?php echo $prev_chapter_link; ?>" class="w-full md:w-auto px-6 py-4 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 transition-all group <?php echo $prev_chapter_class; ?>">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            <span class="font-bold">Chương Trước</span>
                        </a>

                        <a href="<?php echo esc_url($novel_permalink); ?>" class="hidden md:flex flex-col items-center justify-center text-gray-400 hover:text-primary transition-colors">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Danh Sách</span>
                        </a>

                        <a href="<?php echo $next_chapter_link; ?>" class="w-full md:w-auto px-6 py-4 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 transition-all group <?php echo $next_chapter_class; ?>">
                            <span class="font-bold">Chương Sau</span>
                            <svg class="w-5 h-5 ml-3 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>

                    </div>
                    
                    <!-- Mobile List Button -->
                    <div class="md:hidden mt-4">
                        <a href="<?php echo esc_url($novel_permalink); ?>" class="block w-full text-center py-3 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400 text-sm font-bold">
                            Danh Sách Chương
                        </a>
                    </div>
                </div>

            </article>
        </div>

    <?php endwhile; endif; ?>
    
</main>

<script>
// Reading Progress Bar
window.addEventListener('scroll', () => {
    const totalHeight = document.body.scrollHeight - window.innerHeight;
    const progress = (window.pageYOffset / totalHeight) * 100;
    document.getElementById('reading-progress').style.transform = `scaleX(${progress / 100})`;
});

// Sticky Toolbar Auto-Hide/Show
let lastScrollY = window.scrollY;
const toolbar = document.getElementById('sticky-toolbar');

window.addEventListener('scroll', () => {
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
        toolbar.classList.add('-translate-y-full'); // Hide on scroll down
    } else {
        toolbar.classList.remove('-translate-y-full'); // Show on scroll up
    }
    lastScrollY = window.scrollY;
});
</script>

<?php get_footer(); ?>
