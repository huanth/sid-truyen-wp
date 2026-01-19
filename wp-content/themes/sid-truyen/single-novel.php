<?php get_header(); ?>

<main class="site-main py-8 bg-gray-50 dark:bg-dark-bg min-h-screen">
    <div class="container mx-auto px-4">
        
        <?php sid_truyen_breadcrumbs(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Novel Info (Sidebar on Desktop) -->
            <aside class="lg:col-span-1">
                <div class="bg-white dark:bg-dark-surface p-4 rounded-lg shadow-sm sticky top-24">
                    <!-- Cover Image -->
                    <div class="aspect-[2/3] bg-gray-200 dark:bg-gray-700 rounded mb-4 flex items-center justify-center relative overflow-hidden">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                        <?php else : ?>
                            <div class="text-4xl text-gray-400 font-bold select-none">
                                <?php echo substr( get_the_title(), 0, 1 ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 text-center">
                        <?php the_title(); ?>
                    </h1>
                    
                    <?php 
                    $author_name = get_post_meta( get_the_ID(), '_sid_novel_author', true ); 
                    if ( $author_name ) :
                    ?>
                    <div class="text-sm text-center text-gray-500 dark:text-gray-400 mb-4">
                        <?php echo sprintf( __( 'Tác giả: %s', 'sid-truyen' ), esc_html( $author_name ) ); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php
                    $status = get_post_meta( get_the_ID(), '_sid_novel_status', true );
                    $status_label = ucfirst($status);
                    if($status === 'ongoing') $status_label = 'Đang ra';
                    if($status === 'completed') $status_label = 'Hoàn thành';
                    if($status === 'paused') $status_label = 'Tạm dừng';
                    
                    $status_class = 'bg-gray-100 text-gray-700';
                    if($status === 'ongoing') $status_class = 'bg-green-100 text-green-700';
                    if($status === 'completed') $status_class = 'bg-blue-100 text-blue-700';
                    if($status === 'paused') $status_class = 'bg-red-100 text-red-700';
                    ?>
                    <div class="flex justify-center space-x-2 mb-4">
                         <span class="px-2 py-1 text-xs rounded-full <?php echo $status_class; ?>">
                             <?php echo $status_label ? esc_html($status_label) : 'Chưa rõ'; ?>
                         </span>
                    </div>

                    <!-- Genres -->
                    <?php
                    $genres = get_the_terms( get_the_ID(), 'genre' );
                    if ( $genres && ! is_wp_error( $genres ) ) :
                    ?>
                    <div class="flex flex-wrap justify-center gap-2 mb-4">
                        <?php foreach ( $genres as $genre ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $genre ) ); ?>" class="px-2 py-1 text-xs font-bold text-primary bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-md hover:bg-primary hover:text-white transition-all">
                                <?php echo esc_html( $genre->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php endif; ?>

                    <!-- Ebook Download Button -->
                    <div class="mb-6 group">
                        <a href="<?php echo add_query_arg(['ebook_download' => '1', 'post_id' => get_the_ID()], home_url()); ?>" class="relative flex items-center justify-between w-full p-4 bg-gradient-to-br from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 text-white rounded-xl shadow-lg border border-gray-700 hover:border-emerald-500 hover:shadow-emerald-500/20 transition-all duration-300 group-hover:-translate-y-1 overflow-hidden">
                            <!-- Background Glow -->
                            <div class="absolute inset-0 bg-emerald-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <div class="z-10 flex flex-col items-start">
                                <span class="text-xs text-emerald-400 font-bold uppercase tracking-wider mb-1">Ebook Full</span>
                                <span class="font-bold text-lg leading-none">Tải Xuống Ngay</span>
                            </div>
                            
                            <div class="z-10 w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-5 h-5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </div>
                        </a>
                        <div class="mt-2 flex items-center justify-center text-[10px] text-gray-500 dark:text-gray-400 space-x-2">
                            <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Định dạng .txt</span>
                            <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Cập nhật 24/7</span>
                        </div>
                    </div>


                </div>
            </aside>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Description Section -->
                <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm">
                   <h2 class="text-xl font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
                        Giới Thiệu
                    </h2>
                    <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4 font-sans">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Chapter List Section -->
                <div class="bg-white dark:bg-dark-surface p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-200 dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">
                        Danh Sách Chương
                    </h2>

                    <!-- Enhanced Chapter Search Input -->
                    <div class="mb-6">
                        <div class="relative group">
                            <!-- Search Icon -->
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <svg id="search-icon" class="w-5 h-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <!-- Loading Spinner (hidden by default) -->
                                <svg id="loading-spinner" class="hidden w-5 h-5 text-primary animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            
                            <!-- Input Field -->
                            <input 
                                type="text" 
                                id="chapter-search" 
                                placeholder="Tìm chương nhanh (nhập số chương hoặc tên)..." 
                                class="w-full pl-12 pr-12 py-4 text-sm border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-300 focus:ring-4 focus:ring-primary/20 focus:shadow-lg"
                                data-novel-id="<?php echo get_the_ID(); ?>"
                            />
                            
                            <!-- Clear Button -->
                            <button 
                                id="clear-search" 
                                class="hidden absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                aria-label="Clear search"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            
                            <!-- Gradient Border Effect -->
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-purple-500 to-pink-500 opacity-0 group-focus-within:opacity-100 transition-opacity duration-300 -z-10 blur-sm"></div>
                        </div>
                        
                        <!-- Search Results Count -->
                        <div id="search-results-count" class="hidden mt-2 text-sm text-gray-500 dark:text-gray-400"></div>
                    </div>

                    <div id="chapter-list">
                        <style>
                            #chapter-list {
                                display: block; /* Changed from grid */
                            }
                            @media (min-width: 768px) {
                                #chapter-list {
                                    column-count: 2;
                                    column-gap: 1rem;
                                }
                                #chapter-list .chapter-item {
                                    break-inside: avoid;
                                    margin-bottom: 0.5rem;
                                }
                                /* Force pagination to span across all columns */
                                #pagination-container {
                                    column-span: all;
                                    margin-top: 1.5rem;
                                }
                            }
                            @media (max-width: 767px) {
                                #chapter-list .chapter-item {
                                    margin-bottom: 0.5rem;
                                }
                            }
                        </style>
<?php
                        $novel_id = get_the_ID();
                        // Prioritize 'chapters_page' query parameter for robust pagination
                        // We use 'chapters_page' instead of 'page' because 'page' is a reserved WP public var for single post content pagination
                        $paged = isset( $_GET['chapters_page'] ) ? absint( $_GET['chapters_page'] ) : 1;
                        
                        // Fallback to standard WP query vars if custom param is missing but we're in a rewrite context
                        if ( $paged <= 1 ) {
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
                        }
                        
                        // Query Chapters linked to this novel
                        $args = array(
                            'post_type' => 'chapter',
                            'posts_per_page' => 20,
                            'paged' => $paged,
                            'meta_key' => '_sid_chapter_parent_novel',
                            'meta_value' => $novel_id,
                            'orderby' => 'date',
                            'order' => 'DESC' // Newest first
                        );
                        $chapter_query = new WP_Query($args);

                        if ($chapter_query->have_posts()) :
                            while ($chapter_query->have_posts()) : $chapter_query->the_post();
                                // Check if chapter is new (posted within 24 hours)
                                $post_time = get_the_time('U');
                                $current_time = current_time('timestamp');
                                $time_diff = $current_time - $post_time;
                                $is_new = ($time_diff < 86400); // 86400 seconds = 24 hours
                                ?>
                                <a href="<?php the_permalink(); ?>" class="chapter-item text-sm text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded transition-colors block group">
                                    <span class="flex items-center gap-2">
                                        <span class="truncate"><?php the_title(); ?></span>
                                        <?php if ($is_new) : ?>
                                            <span style="padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: bold; background: linear-gradient(to right, #ef4444, #f97316); color: white; border-radius: 9999px; text-transform: uppercase; flex-shrink: 0; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">NEW</span>
                                        <?php endif; ?>
                                    </span>
                                </a>
                                <?php
                            endwhile;
                            
                            // Pagination
                            if ($chapter_query->max_num_pages > 1) :
                                ?>
                                <div class="col-span-full mt-6 pt-4 border-t border-gray-200 dark:border-gray-700" id="pagination-container">
                                    <div class="flex justify-center items-center space-x-2">
                                        <?php
                                        $pagination = paginate_links(array(
                                            'base' => add_query_arg( 'chapters_page', '%#%' ),
                                            'format' => '',
                                            'current' => $paged,
                                            'total' => $chapter_query->max_num_pages,
                                            'prev_text' => '&laquo; Trước',
                                            'next_text' => 'Sau &raquo;',
                                            'type' => 'array',
                                            'before_page_number' => '<span class="px-3 py-2 text-sm">',
                                            'after_page_number' => '</span>'
                                        ));
                                        
                                        if ($pagination) {
                                            foreach ($pagination as $page) {
                                                // Style current page differently
                                                if (strpos($page, 'current') !== false) {
                                                    echo str_replace('page-numbers current', 'page-numbers current bg-primary text-white font-bold rounded', $page);
                                                } else {
                                                    echo str_replace('page-numbers', 'page-numbers text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors', $page);
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                            endif;
                            
                            wp_reset_postdata();
                        else :
                            echo '<p class="text-gray-500">Chưa có chương nào.</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
(function() {
    const searchInput = document.getElementById('chapter-search');
    const chapterList = document.getElementById('chapter-list');
    const clearButton = document.getElementById('clear-search');
    const searchIcon = document.getElementById('search-icon');
    const loadingSpinner = document.getElementById('loading-spinner');
    const resultsCount = document.getElementById('search-results-count');
    const paginationContainer = document.getElementById('pagination-container');
    
    if (!searchInput || !chapterList) return;
    
    // Store original chapters for restoration
    const originalChapters = chapterList.innerHTML;
    let searchTimeout;
    
    // Show/hide clear button
    searchInput.addEventListener('input', function() {
        if (this.value.trim().length > 0) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
            // Restore original chapters when input is empty
            restoreOriginalChapters();
        }
        
        // Debounced AJAX search
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => performSearch(this.value.trim()), 300);
    });
    
    // Clear button functionality
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        restoreOriginalChapters();
        searchInput.focus();
    });
    
    function performSearch(searchTerm) {
        if (searchTerm.length === 0) {
            return;
        }
        
        // Show loading state
        showLoading(true);
        
        // AJAX request
        const formData = new FormData();
        formData.append('action', 'search_chapters');
        formData.append('search_term', searchTerm);
        formData.append('novel_id', searchInput.dataset.novelId);
        formData.append('nonce', '<?php echo wp_create_nonce("chapter_search_nonce"); ?>');
        
        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            
            if (data.success && data.data.chapters) {
                displaySearchResults(data.data.chapters);
            } else {
                displayNoResults();
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showLoading(false);
            displayNoResults();
        });
    }
    
    function showLoading(isLoading) {
        if (isLoading) {
            searchIcon.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
        } else {
            searchIcon.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
        }
    }
    
    function displaySearchResults(chapters) {
        // Hide pagination during search
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        
        // Show results count
        resultsCount.classList.remove('hidden');
        resultsCount.textContent = `Tìm thấy ${chapters.length} chương`;
        
        // Clear and display results
        chapterList.innerHTML = '';
        
        if (chapters.length > 0) {
            chapters.forEach(chapter => {
                const chapterLink = document.createElement('a');
                chapterLink.href = chapter.permalink;
                chapterLink.className = 'chapter-item text-sm text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800 p-2 rounded transition-colors block group';
                
                const span = document.createElement('span');
                span.className = 'flex items-center gap-2';
                
                const title = document.createElement('span');
                title.className = 'truncate';
                title.textContent = chapter.title;
                span.appendChild(title);
                
                // Add NEW badge if applicable
                if (chapter.is_new) {
                    const badge = document.createElement('span');
                    badge.style.cssText = 'padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: bold; background: linear-gradient(to right, #ef4444, #f97316); color: white; border-radius: 9999px; text-transform: uppercase; flex-shrink: 0; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;';
                    badge.textContent = 'NEW';
                    span.appendChild(badge);
                }
                
                chapterLink.appendChild(span);
                chapterList.appendChild(chapterLink);
            });
        }
    }
    
    function displayNoResults() {
        resultsCount.classList.remove('hidden');
        resultsCount.textContent = 'Không tìm thấy chương nào';
        
        if (paginationContainer) {
            paginationContainer.style.display = 'none';
        }
        
        chapterList.innerHTML = '<p class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">Không tìm thấy chương nào phù hợp với từ khóa tìm kiếm.</p>';
    }
    
    function restoreOriginalChapters() {
        chapterList.innerHTML = originalChapters;
        resultsCount.classList.add('hidden');
        
        if (paginationContainer) {
            paginationContainer.style.display = '';
        }
    }
})();
</script>

<?php get_footer(); ?>
