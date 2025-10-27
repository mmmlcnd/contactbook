<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4 md:p-8">
    <div class="max-w-xl mx-auto bg-white shadow-2xl rounded-2xl p-6 md:p-10 border border-gray-100">

        <!-- ヘッダーとウェルカムメッセージ -->
        <div class="text-center mb-8 border-b pb-4">
            <h1 class="text-4xl font-extrabold text-red-700 mb-2">
                管理者ダッシュボード
            </h1>
            <p class="text-xl text-gray-600 font-medium">
                ようこそ、<span class="text-red-600 font-bold"><?php echo e($_SESSION['user_name'] ?? 'ゲスト'); ?></span> さん
            </p>
        </div>

        <!-- メインアクションカード -->
        <div class="space-y-6">

            <!-- アカウント管理カード -->
            <a href="<?php echo e(route('admins.create')); ?>" class="block p-5 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition duration-300 ease-in-out transform hover:scale-[1.01] shadow-md">
                <div class="flex items-center">
                    <div class="text-red-500 mr-4">
                        <i class="fas fa-user-shield text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-red-800">生徒・教師アカウント管理</h3>
                        <p class="text-sm text-gray-500 mt-1">新しい生徒および教師のアカウント登録を行います。</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                </div>
            </a>

        </div>

        <!-- ログアウト -->
        <div class="mt-8 pt-4 border-t border-gray-200">
            <form action="<?php echo e(route('logout', ['role' => 'admin'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-150 ease-in-out shadow-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> ログアウト
                </button>
            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/admins/admin_dashboard.blade.php ENDPATH**/ ?>