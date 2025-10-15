<div class="dashboard-container">
    <h2>管理者ダッシュボード</h2>

    <?php //<p>ようこそ、{{ auth()->guard('admin')->user()->name }} さん</p> -->
    ?>

    <p>ようこそ、さん</p>

    <ul>
        <li><a href="<?php echo e(route('create')); ?>">生徒・教師アカウント管理</a></li>
        <li><a href="<?php echo e(route('classes')); ?>">クラス管理</a></li>

        <?php /*
        //
        <li><a href="{{ route('entries.status') }}">全生徒の提出状況確認</a></li>

        <!-- <li>
            <form action="{{ route('logout', ['role' => 'admin']) }}" method="POST">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> --> */ ?>
    </ul>
</div>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/admins/admin_dashboard.blade.php ENDPATH**/ ?>