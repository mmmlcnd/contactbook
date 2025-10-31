<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4 md:p-8">
    <div class="max-w-xl mx-auto bg-white shadow-2xl rounded-2xl p-6 md:p-10 border border-gray-100">

        <!-- ヘッダーとウェルカムメッセージ -->
        <div class="text-center mb-8 border-b pb-4">
            <h1 class="text-4xl font-extrabold text-indigo-700 mb-2">
                生徒ダッシュボード
            </h1>
            <p class="text-xl text-gray-600 font-medium">
                ようこそ、<span class="text-indigo-600 font-bold"><?php echo e($_SESSION['user_name'] ?? 'ゲスト'); ?></span> さん
            </p>
        </div>

        <!-- メインアクションカード -->
        <div class="space-y-6">

            <!-- 連絡帳提出カード -->
            <a href="<?php echo e(route('students.entries.create')); ?>" class="block p-5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition duration-300 ease-in-out transform hover:scale-[1.01] shadow-md">
                <div class="flex items-center">
                    <div class="text-indigo-500 mr-4">
                        <i class="fas fa-edit text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-indigo-800">連絡帳を提出する</h3>
                        <p class="text-sm text-gray-500 mt-1">今日の体調や連絡事項を先生に伝えましょう。</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                </div>
            </a>

            <!-- 過去の提出記録カード -->
            <a href="<?php echo e(route('students.entries.past')); ?>" class="block p-5 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl transition duration-300 ease-in-out transform hover:scale-[1.01] shadow-md">
                <div class="flex items-center">
                    <div class="text-teal-500 mr-4">
                        <i class="fas fa-history text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-teal-800">過去の提出記録を見る</h3>
                        <p class="text-sm text-gray-500 mt-1">過去に提出した連絡帳を確認します。</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 ml-auto"></i>
                </div>
            </a>

        </div>

        <!-- ログアウト -->
        <?php /* <div class="mt-8 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout', ['role' => 'student']) }}">
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
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/students/student_dashboard.blade.php ENDPATH**/ ?>