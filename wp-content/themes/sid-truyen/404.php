<?php get_header(); ?>

<main class="site-main bg-gray-50 dark:bg-[#121212] flex items-center justify-center min-h-[80vh] px-4 relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl -z-10"></div>

    <div class="text-center max-w-lg mx-auto relative z-10">
        <!-- Large 404 Text -->
        <h1 class="text-[150px] leading-none font-black text-gray-200 dark:text-gray-800 select-none animate-pulse">
            404
        </h1>
        
        <div class="relative -mt-16 sm:-mt-20 mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                Lạc Đường Rồi!
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                Xin lỗi, trang bạn đang tìm kiếm có vẻ như không tồn tại hoặc đã bị chuyển đi nơi khác. Hãy thử tìm kiếm truyện khác xem sao?
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?php echo home_url('/'); ?>" class="flex items-center px-8 py-3 bg-primary hover:opacity-90 text-white font-bold rounded-full shadow-lg shadow-primary/30 transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Về Trang Chủ
                </a>
                
                <a href="<?php echo home_url('/?s='); ?>" class="flex items-center px-8 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-full font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Tìm Truyện
                </a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
