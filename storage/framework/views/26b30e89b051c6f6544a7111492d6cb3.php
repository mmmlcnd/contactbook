<?php $__env->startSection('title', '教師ログイン'); ?>

<?php $__env->startSection('form'); ?>
<form method="POST" action="<?php echo e(route('login.teacher')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-2">メールアドレス</label>
        <input type="email" name="email" id="email" required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="mb-6">
        <label for="password" class="block text-gray-700 font-medium mb-2">パスワード</label>
        <input type="password" name="password" id="password" required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <button type="submit" class="btn btn-blue">ログイン</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.login', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/auth/teacher_login.blade.php ENDPATH**/ ?>