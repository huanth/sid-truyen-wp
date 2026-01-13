<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png" type="image/png">
	<?php wp_head(); ?>
	<style>
		/* Fix Header covered by Admin Bar */
		.admin-bar header.fixed {
			top: 32px;
            z-index: 9999;
		}
		@media screen and (max-width: 782px) {
			.admin-bar header.fixed {
				top: 46px;
			}
		}
		/* Hide scrollbar for Chrome, Safari and Opera */
		.no-scrollbar::-webkit-scrollbar {
			display: none;
		}
		/* Hide scrollbar for IE, Edge and Firefox */
		.no-scrollbar {
			-ms-overflow-style: none;  /* IE and Edge */
			scrollbar-width: none;  /* Firefox */
		}
	</style>
</head>

<body <?php body_class( 'bg-gray-50 text-gray-800 dark:bg-[#121212] dark:text-gray-300 antialiased' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
	<!-- Header -->
	<header class="fixed w-full top-0 z-[100000] bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shadow-sm transition-all duration-300">
		<nav class="container mx-auto px-4 py-2 flex flex-wrap lg:flex-nowrap items-center justify-between gap-y-2">
			
			<!-- Logo -->
			<div class="flex-shrink-0 flex items-center">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 group relative">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>" class="relative w-[140px] h-[40px] md:w-[250px] md:h-[70px] object-contain drop-shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:drop-shadow-md">
				</a>
			</div>

			<!-- Desktop Navigation -->
			<!-- Logic: 'hidden' (mobile default) -> 'lg:flex' (desktop visible) -->
			<div class="hidden lg:flex items-center gap-1 ml-4">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="px-3 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 whitespace-nowrap">
					Trang chủ
				</a>
				
				<a href="<?php echo get_post_type_archive_link( 'novel' ); ?>" class="px-3 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 whitespace-nowrap">
					Tất cả truyện
				</a>

				<a href="<?php echo esc_url( add_query_arg( 'v_sort', 'views', get_post_type_archive_link( 'novel' ) ) ); ?>" class="px-3 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 whitespace-nowrap">
					Truyện hot 🔥
				</a>

				<a href="<?php echo esc_url( add_query_arg( 'v_status', 'completed', get_post_type_archive_link( 'novel' ) ) ); ?>" class="px-3 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 whitespace-nowrap">
					Truyện hoàn thành
				</a>
			</div>

			<!-- Right Actions -->
			<div class="flex items-center gap-3">
				<!-- Search Button -->
				<button id="search-toggle" class="p-3 rounded-xl text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-primary/5 transition-all hover:scale-110 hover:shadow-lg group" title="Tìm kiếm">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
				</button>

				<!-- Mobile Menu Toggle -->
				<!-- Logic: 'block' (mobile default) -> 'lg:hidden' (desktop hidden) -->
				<button id="mobile-menu-toggle" class="block lg:hidden p-3 rounded-xl text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-primary/5 transition-all hover:scale-110 hover:shadow-lg" title="Menu">
					<svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
					</svg>
					<svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				</button>
			</div>

			<!-- Mobile Navigation (Hidden by default, toggled via JS) -->
			<div id="mobile-menu" class="hidden lg:hidden flex-col w-full basis-full order-last mt-2 border-t border-gray-100 dark:border-gray-800 pt-2 animate-fadeIn">
				<div class="grid grid-cols-2 gap-2">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-xl hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-2">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
						Trang chủ
					</a>
					<a href="<?php echo get_post_type_archive_link( 'novel' ); ?>" class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-xl hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-2">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
						Tất cả truyện
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'v_sort', 'views', get_post_type_archive_link( 'novel' ) ) ); ?>" class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-xl hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-2">
						<svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
						Truyện hot
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'v_status', 'completed', get_post_type_archive_link( 'novel' ) ) ); ?>" class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-xl hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-2">
						<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
						Đã hoàn thành
					</a>
				</div>
			</div>

		</nav>
	</header>
	
	<script>
		document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
			const menu = document.getElementById('mobile-menu');
			const menuIcon = document.getElementById('menu-icon');
			const closeIcon = document.getElementById('close-icon');
			
			menu.classList.toggle('hidden');
			menu.classList.toggle('flex');
			menuIcon.classList.toggle('hidden');
			closeIcon.classList.toggle('hidden');
		});
	</script>
    
    <!-- Search Modal -->
    <div id="search-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fadeIn">
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-primary via-primary to-secondary p-10">
                <button id="search-close" class="absolute top-6 right-6 p-2 hover:bg-white/20 rounded-full transition-all">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-2">Tìm kiếm truyện</h3>
                    <p class="text-white/80">Khám phá kho truyện phong phú</p>
                </div>
            </div>

            <!-- Search Form -->
            <div class="p-10">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="hidden" name="post_type" value="novel">
                    <div class="relative">
                        <input 
                            type="search" 
                            name="s" 
                            placeholder="Nhập tên truyện..." 
                            class="w-full px-6 py-5 pr-36 text-lg border-2 border-gray-200 dark:border-gray-700 rounded-2xl focus:outline-none focus:border-primary dark:bg-gray-800 dark:text-white shadow-sm transition-all"
                            autofocus
                        >
                        <button type="submit" class="absolute right-2 top-2 px-8 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Tìm</span>
                        </button>
                    </div>
                </form>
                
                <!-- Tips -->
                <div class="mt-6 flex items-center justify-center gap-8 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 font-mono text-xs shadow-sm">Enter</kbd>
                        <span>Tìm kiếm</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 font-mono text-xs shadow-sm">Esc</kbd>
                        <span>Đóng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Spacer for fixed header -->
    <div class="h-20"></div>

	<div id="content" class="site-content flex-grow">
