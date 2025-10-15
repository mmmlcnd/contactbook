<?php $__env->startSection('title', 'クラス管理'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">管理者：クラス管理画面（テスト表示）</h1>

    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
        <p class="font-bold">認証成功！</p>
        <p>この画面が表示されているということは、Admin認証とルーティングが正常に機能しています。</p>
    </div>

    <div class="mt-6 p-4 bg-white shadow rounded-lg">
        <p class="text-gray-600">ここからクラスのCRUD操作のUIを構築していきます。</p>
        <p class="text-sm mt-2 text-gray-400">（レイアウトファイルは 'layouts.app' を使用しています）</p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/admins/admin_manage_classes.blade.php ENDPATH**/ ?>