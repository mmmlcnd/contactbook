<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 w-full max-w-sm sm:max-w-md lg:max-w-lg text-center">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">ようこそ！</h1>
        <p class="text-gray-600 mb-8 text-sm sm:text-base">架空私立中学校 連絡帳管理システムへようこそ。</p>

        <div class="space-y-4">
            <a href="<?php echo e(route('login.student')); ?>"
                class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 sm:py-3 rounded transition">
                🎓 生徒ログイン
            </a>
            <a href="<?php echo e(route('login.teacher')); ?>"
                class="block w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 sm:py-3 rounded transition">
                👨‍🏫 教師ログイン
            </a>
            <a href="<?php echo e(route('login.admin')); ?>"
                class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 sm:py-3 rounded transition">
                🛠 管理者ログイン
            </a>
        </div>
    </div>

    <footer class="mt-10 text-gray-500 text-xs sm:text-sm">
        &copy; 2025 架空私立中学校 連絡帳システム
    </footer>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/index.blade.php ENDPATH**/ ?>