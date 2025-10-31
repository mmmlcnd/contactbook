<?php $__env->startSection('title', '連絡帳詳細 - ' . date('Y/m/d', strtotime($entry->record_date))); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto p-4 md:p-8 bg-white shadow-lg rounded-xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
        <a href="<?php echo e(route('students.entries.past')); ?>" class="text-blue-500 hover:text-blue-700 transition duration-150 ease-in-out">
            &larr; 提出履歴に戻る
        </a>
    </h1>

    <div class="bg-indigo-50 p-6 rounded-lg mb-6">
        <h2 class="text-xl font-semibold text-indigo-700 mb-2">
            あなたの連絡帳
        </h2>
        <p class="text-sm text-indigo-500">記録日: <?php echo e(date('Y年n月j日', strtotime($entry->record_date))); ?></p>
    </div>

    <?php if(isset($_SESSION['success_message'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
        <?php echo e($_SESSION['success_message']); ?>

        <?php unset($_SESSION['success_message']); ?>
    </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error_message'])): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
        <?php echo e($_SESSION['error_message']); ?>

        <?php unset($_SESSION['error_message']); ?>
    </div>
    <?php endif; ?>

    <div class="space-y-6">
        <!-- 体調・精神状態 -->
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-sm font-medium text-gray-500">体調 (5段階)</p>
                <p class="text-2xl font-extrabold text-blue-600"><?php echo e($entry->condition_physical); ?></p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-sm font-medium text-gray-500">精神状態 (5段階)</p>
                <p class="text-2xl font-extrabold text-purple-600"><?php echo e($entry->condition_mental); ?></p>
            </div>
        </div>

        <!-- 連絡帳の内容 -->
        <div class="p-5 bg-gray-50 border border-gray-200 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">連絡内容</h3>
            <p class="whitespace-pre-wrap text-gray-800"><?php echo e($entry->content); ?></p>
        </div>

        <!-- 先生からの確認・スタンプ履歴 -->
        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">先生からの確認・スタンプ履歴</h3>

            <?php if($readHistory && $readHistory->count() > 0): ?>
            <?php $__currentLoopData = $readHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (! (is_object($history))): ?>
            <?php continue; ?>
            <?php endif; ?>
            <div class="flex items-center space-x-3 mb-2 p-3 bg-white rounded-lg border border-gray-100 shadow-sm">
                <span class="text-2xl" role="img" aria-label="Stamp">
                    <?php if($history->stamp_name == 'イイネ'): ?> 👍 <?php elseif($history->stamp_name == '頑張ったね'): ?> ✨ <?php elseif($history->stamp_name == 'お大事に'): ?> 🍀 <?php else: ?> 🏷️ <?php endif; ?>
                </span>
                <div class="flex-1">
                    <span class="font-bold text-sm text-gray-800"><?php echo e($history->stamp_name); ?></span>
                    
                    <span class="text-xs text-gray-500 ml-2">
                        (<?php echo e($history->teacher->name ?? '不明な先生'); ?>)
                    </span>
                </div>
                <span class="text-xs text-gray-400">
                    <?php echo e(date('Y-m-d H:i', strtotime($history->stamped_at))); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <p class="text-gray-500 italic">まだどの先生からも確認されていません。</p>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/students/student_entry_detail.blade.php ENDPATH**/ ?>