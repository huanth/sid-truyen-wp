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

                    <button onclick="openModal()" class="hidden md:flex flex-col items-center justify-center text-gray-400 hover:text-primary transition-colors cursor-pointer">
                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span class="text-xs font-bold uppercase tracking-wider">Danh Sách</span>
                    </button>

                    <a href="<?php echo $next_chapter_link; ?>" class="w-full md:w-auto px-6 py-4 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 transition-all group <?php echo $next_chapter_class; ?>">
                        <span class="font-bold">Chương Sau</span>
                        <svg class="w-5 h-5 ml-3 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                </div>
                
                <!-- Mobile List Button -->
                <div class="md:hidden mt-4">
                    <button onclick="openModal()" class="block w-full text-center py-3 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400 text-sm font-bold">
                        Danh Sách Chương
                    </button>
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

                        <button onclick="openModal()" class="hidden md:flex flex-col items-center justify-center text-gray-400 hover:text-primary transition-colors cursor-pointer">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Danh Sách</span>
                        </button>

                        <a href="<?php echo $next_chapter_link; ?>" class="w-full md:w-auto px-6 py-4 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 transition-all group <?php echo $next_chapter_class; ?>">
                            <span class="font-bold">Chương Sau</span>
                            <svg class="w-5 h-5 ml-3 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>

                    </div>
                    
                    <!-- Mobile List Button -->
                    <div class="md:hidden mt-4">
                        <button onclick="openModal()" class="block w-full text-center py-3 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400 text-sm font-bold">
                            Danh Sách Chương
                        </button>
                    </div>
                </div>

            </article>
        </div>

    <?php endwhile; endif; ?>
    
    <!-- Chapter List Modal -->
    <div id="chapter-modal" class="fixed inset-0 z-[100000] hidden opacity-0 transition-opacity duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full h-full flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] flex flex-col transform scale-95 transition-transform duration-300" id="modal-content">
                
                <!-- Header with gradient -->
                <div class="relative p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary/5 to-purple-500/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary to-purple-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    Danh Sách Chương
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                    <?php echo esc_html($novel_title); ?>
                                </p>
                            </div>
                        </div>
                        <button onclick="closeModal()" class="w-11 h-11 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-all hover:rotate-90 duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                
                <!-- Search with icon -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input 
                            type="text" 
                            id="modal-chapter-search" 
                            placeholder="Tìm kiếm chương (số chương, tên chương)..." 
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-2xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all"
                        />
                    </div>
                </div>
                
                <!-- Chapter List with custom scrollbar -->
                <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                    <div id="modal-chapter-list" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <?php
                        if ($parent_novel_id) {
                            $all_chapters_full = get_posts(array(
                                'post_type' => 'chapter',
                                'posts_per_page' => -1,
                                'meta_key' => '_sid_chapter_parent_novel',
                                'meta_value' => $parent_novel_id,
                                'orderby' => 'date',
                                'order' => 'DESC'
                            ));
                            
                            foreach ($all_chapters_full as $chapter) {
                                $post_time = get_the_time('U', $chapter->ID);
                                $current_time = current_time('timestamp');
                                $time_diff = $current_time - $post_time;
                                $is_new = ($time_diff < 86400);
                                $is_current = ($chapter->ID == get_the_ID());
                                ?>
                                <a href="<?php echo get_permalink($chapter->ID); ?>" class="chapter-modal-item group flex items-center gap-3 p-4 rounded-2xl hover:bg-gradient-to-r hover:from-primary/10 hover:to-purple-500/10 dark:hover:from-primary/20 dark:hover:to-purple-500/20 transition-all duration-200 <?php echo $is_current ? 'bg-gradient-to-r from-primary/20 to-purple-500/20 ring-2 ring-primary shadow-lg shadow-primary/20' : 'hover:shadow-md'; ?>" data-title="<?php echo esc_attr(strtolower($chapter->post_title)); ?>">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform <?php echo $is_current ? 'from-primary to-purple-500 text-white' : 'text-gray-500 dark:text-gray-400'; ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary truncate <?php echo $is_current ? 'text-primary dark:text-primary font-bold' : ''; ?>">
                                        <?php echo esc_html($chapter->post_title); ?>
                                    </span>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <?php if ($is_new): ?>
                                            <span class="px-2.5 py-1 text-xs font-bold bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-lg shadow-sm">NEW</span>
                                        <?php endif; ?>
                                        <?php if ($is_current): ?>
                                            <span class="px-2.5 py-1 text-xs font-bold bg-primary text-white rounded-lg shadow-sm">Đang đọc</span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div id="no-results-modal" class="hidden text-center py-16">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Không tìm thấy chương nào</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Thử tìm kiếm với từ khóa khác</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <style>
        /* Custom scrollbar for modal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Modal animations */
        #chapter-modal.show {
            opacity: 1;
        }
        #chapter-modal.show #modal-content {
            transform: scale(1);
        }
    </style>
    
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

// Modal Chapter Search
const modalSearchInput = document.getElementById('modal-chapter-search');
const modalChapterList = document.getElementById('modal-chapter-list');
const noResultsModal = document.getElementById('no-results-modal');
const chapterModal = document.getElementById('chapter-modal');
const modalContent = document.getElementById('modal-content');

// Open modal with animation
function openModal() {
    chapterModal.classList.remove('hidden');
    // Force reflow for animation
    void chapterModal.offsetWidth;
    chapterModal.classList.add('show');
    
    // Scroll to current chapter after modal opens
    setTimeout(() => {
        const currentChapter = document.querySelector('.chapter-modal-item.bg-gradient-to-r.from-primary\\/20');
        if (currentChapter) {
            currentChapter.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }, 350);
}

// Close modal with animation
function closeModal() {
    chapterModal.classList.remove('show');
    setTimeout(() => {
        chapterModal.classList.add('hidden');
        // Clear search when closing
        if (modalSearchInput) {
            modalSearchInput.value = '';
            // Trigger search to reset view
            const event = new Event('input');
            modalSearchInput.dispatchEvent(event);
        }
    }, 300);
}

// Modal search functionality
if (modalSearchInput) {
    modalSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const chapterItems = modalChapterList.querySelectorAll('.chapter-modal-item');
        let visibleCount = 0;
        
        chapterItems.forEach(item => {
            const chapterTitle = item.getAttribute('data-title');
            if (chapterTitle.includes(searchTerm)) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            modalChapterList.classList.add('hidden');
            noResultsModal.classList.remove('hidden');
        } else {
            modalChapterList.classList.remove('hidden');
            noResultsModal.classList.add('hidden');
        }
    });
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !chapterModal.classList.contains('hidden')) {
        closeModal();
    }
});
</script>

<?php get_footer(); ?>
