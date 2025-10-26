<?php
// $currentMonth (例: '2025-10') を DateTime オブジェクトに変換
$dateObj = DateTime::createFromFormat('Y-m', $currentMonth);
// 'Y年n月' の形式にフォーマット
$displayMonth = $dateObj ? $dateObj->format('Y年n月') : $currentMonth;
?>



<?php $__env->startSection('title', '提出済み連絡帳一覧'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-xl p-6 md:p-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 border-b pb-3 text-center">
            連絡帳の提出履歴
        </h1>

        
        <div class="flex items-center justify-between bg-indigo-50 p-4 rounded-lg mb-8 shadow-inner">
            <a href="?month=<?php echo e($previousMonth); ?>" class="text-indigo-600 hover:text-indigo-800 transition duration-150">
                <i class="fas fa-chevron-left"></i> 前月へ
            </a>
            <h2 class="text-xl md:text-2xl font-bold text-indigo-800">
                <?php echo e($displayMonth); ?> の連絡帳
            </h2>
            <a href="?month=<?php echo e($nextMonth); ?>" class="text-indigo-600 hover:text-indigo-800 transition duration-150 <?php if($isFutureMonth): ?> opacity-50 cursor-default <?php endif; ?>">
                次月へ <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        
        <div class="space-y-6">
            <?php if(empty($entries)): ?>
            
            <div class="p-8 text-center bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                <p class="text-gray-600 text-lg font-medium">
                    <?php echo e($displayMonth); ?>の提出履歴はありません。
                </p>
            </div>
            <?php else: ?>
            <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out p-5">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-3 mb-3">
                    
                    <div class="flex items-center mb-2 md:mb-0">
                        <span class="text-3xl font-extrabold text-gray-800 mr-3">
                            <?php echo e(date('d', strtotime($entry->report_date))); ?>

                        </span>
                        <span class="text-base font-semibold text-indigo-600">
                            (<?php echo e(date('曜', strtotime($entry->report_date))); ?>)
                        </span>
                    </div>

                    
                    <a href="/students/entries/<?php echo e($entry->id); ?>" class="text-sm font-semibold text-white bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-full shadow-md transition duration-150 ease-in-out">
                        詳細を見る <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col items-start p-3 bg-red-50 rounded-lg">
                        <span class="text-xs font-medium text-red-500 uppercase tracking-wider">体調</span>
                        <div class="text-2xl font-extrabold text-red-700 mt-1">
                            <?php echo e($entry->condition_physical); ?> / 5
                        </div>
                    </div>
                    <div class="flex flex-col items-start p-3 bg-blue-50 rounded-lg">
                        <span class="text-xs font-medium text-blue-500 uppercase tracking-wider">メンタル</span>
                        <div class="text-2xl font-extrabold text-blue-700 mt-1">
                            <?php echo e($entry->condition_mental); ?> / 5
                        </div>
                    </div>
                </div>

                
                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">
                        提出内容の抜粋
                    </h4>
                    <p class="text-gray-600 text-base line-clamp-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <?php echo e(mb_substr($entry->content, 0, 100)); ?>...
                    </p>
                    <?php if($entry->teacher_comment): ?>
                    <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <span class="text-xs font-bold text-yellow-700 block mb-1"><i class="fas fa-comment-dots mr-1"></i> 先生からのコメント</span>
                        <p class="text-sm text-yellow-800 line-clamp-2">
                            <?php echo e(mb_substr($entry->teacher_comment, 0, 50)); ?>...
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        
        <div class="mt-10 pt-6 border-t border-gray-200 flex justify-center">
            <a href="/students/entries/create" class="inline-flex items-center text-lg font-bold text-indigo-600 hover:text-indigo-800 transition duration-150">
                <i class="fas fa-plus-circle mr-2"></i> 新しく連絡帳を提出する
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/students/student_past_entries.blade.php ENDPATH**/ ?>