<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <h2>生徒ダッシュボード</h2>

    <?php /*
    <!-- <p>ようこそ、{{ auth()->guard('student')->user()->name}}さん</p> -->*/ ?>

    <ul>
        <li><a href="<?php echo e(route('students.entries.create')); ?>">連絡帳を提出する</a></li>
        <li><a href="<?php echo e(route('students.entries.past')); ?>">過去の提出記録を見る</a></li>
        <?php /* <!-- <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> --> */ ?>
    </ul>


</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/students/student_dashboard.blade.php ENDPATH**/ ?>