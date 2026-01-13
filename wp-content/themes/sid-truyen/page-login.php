<?php
/**
 * Template Name: Login Page
 */

get_header(); 
?>

<div class="min-h-screen bg-gray-50 dark:bg-dark-bg flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-gray-100">
            Đăng nhập
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            Hoặc
            <a href="<?php echo home_url('/dang-ky'); ?>" class="font-medium text-primary hover:text-secondary">
                đăng ký tài khoản mới
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="py-8 px-4 shadow sm:rounded-lg sm:px-10 bg-white dark:bg-dark-surface border border-gray-200 dark:border-gray-700">
            
            <?php if(isset($_GET['login']) && $_GET['login'] == 'failed'): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4" role="alert">
                    <p class="text-sm text-red-700">Tên đăng nhập hoặc mật khẩu không đúng.</p>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="" method="POST">
                <?php wp_nonce_field( 'sid_login_action', 'sid_login_nonce' ); ?>
                <div>
                    <label for="sid_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tên đăng nhập
                    </label>
                    <div class="mt-1">
                        <input id="sid_username" name="sid_username" type="text" autocomplete="username" required class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div>
                    <label for="sid_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mật khẩu
                    </label>
                    <div class="mt-1">
                        <input id="sid_password" name="sid_password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="sid_remember" name="sid_remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="sid_remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Ghi nhớ
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="<?php echo wp_lostpassword_url(); ?>" class="font-medium text-primary hover:text-secondary">
                            Quên mật khẩu?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Đăng nhập
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php get_footer(); ?>
