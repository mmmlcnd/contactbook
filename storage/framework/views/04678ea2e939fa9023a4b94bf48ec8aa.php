<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4 md:p-8">
    <div class="max-w-xl mx-auto bg-white shadow-2xl rounded-2xl p-6 md:p-10 border border-gray-100">

        <!-- ヘッダーとウェルカムメッセージ -->
        <div class="text-center mb-8 border-b pb-4">
            <h1 class="text-4xl font-extrabold text-green-700 mb-2">
                教師ダッシュボード
            </h1>
            <p class="text-xl text-gray-600 font-medium">
                ようこそ、<span class="text-green-600 font-bold"><?php echo e($_SESSION['user_name'] ?? 'ゲスト'); ?></span> 先生
            </p>
        </div>

        <!-- メインアクションカード -->
        <div class="space-y-6">

            <!-- 提出状況確認カード -->
            <a href="<?php echo e(route('teachers.status')); ?>" class="block p-5 bg-green-50 hover:bg-green-100 border border-green-200 rounded-xl transition duration-300 ease-in-out transform hover:scale-[1.01] shadow-md">
                <div class="flex items-center">
                    <div class="text-green-500 mr-4">
                        <i class="fas fa-tasks text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-green-800">担当クラスの提出状況確認</h3>
                        <p class="text-sm text-gray-500 mt-1">生徒の未読・既読の連絡帳を一覧で確認し、コメント・スタンプを行います。</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                </div>
            </a>

            <!-- 過去の記録確認カード -->
            <a href="<?php echo e(route('teachers.entries.past')); ?>" class="block p-5 bg-lime-50 hover:bg-lime-100 border border-lime-200 rounded-xl transition duration-300 ease-in-out transform hover:scale-[1.01] shadow-md">
                <div class="flex items-center">
                    <div class="text-lime-600 mr-4">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-lime-800">過去の記録確認</h3>
                        <p class="text-sm text-gray-500 mt-1">過去の提出記録と処理済み連絡帳を月別に確認します。</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                </div>
            </a>

        </div>

        <!-- ログアウト -->
        <?php /* <div class="mt-8 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout', ['role' => 'teacher']) }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-150 ease-in-out shadow-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> ログアウト
                </button>
            </form>
        </div>
        */ ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/teachers/teacher_dashboard.blade.php ENDPATH**/ ?>