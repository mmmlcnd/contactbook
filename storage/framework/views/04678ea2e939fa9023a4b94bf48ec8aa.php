<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <h2>教師ダッシュボード</h2>

    <p>ようこそ、<?php echo e($_SESSION['user_name']); ?>さん</p>
    <ul>
        <li><a href="<?php echo e(route('teachers.status')); ?>">担当クラスの提出状況確認</a></li>
        <li><a href="<?php echo e(route('teachers.status')); ?>">過去の記録確認</a></li>
        <?php /* <!-- <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> --> */ ?>
    </ul>


</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/teachers/teacher_dashboard.blade.php ENDPATH**/ ?>