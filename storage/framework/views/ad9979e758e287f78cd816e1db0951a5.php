<?php
// Laravelのセッションヘルパーを使用
$success = session('success');
$error = session('error');
// フラッシュデータや一時データをクリア
session()->forget(['success', 'error', 'user_type_temp']);

// コントローラーから $selectedUserType が渡されない場合に備えてデフォルト値を設定
// コントローラーから渡される場合は、その値をそのまま使用
$selectedUserType = $selectedUserType ?? 'student';
?>



<?php $__env->startSection('title', 'ユーザー作成'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">ユーザー作成</h1>

    
    <?php if($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo e($success); ?></span>
    </div>
    <?php endif; ?>
    <?php if($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo e($error); ?></span>
    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4 border-b pb-2 text-gray-700">新規ユーザー作成</h2>

        <form method="POST" action="<?php echo e(url('/admins/create')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label for="user_type" class="block text-sm font-medium text-gray-700">ユーザー種別</label>
                    <select id="user_type" name="user_type" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm"
                        onchange="toggleFields()">
                        
                        <option value="student" <?php echo e($selectedUserType === 'student' ? 'selected' : ''); ?>>生徒</option>
                        <option value="teacher" <?php echo e($selectedUserType === 'teacher' ? 'selected' : ''); ?>>教師</option>
                        <option value="admin" <?php echo e($selectedUserType === 'admin' ? 'selected' : ''); ?>>管理者</option>
                    </select>
                </div>
            </div>

            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
                
                <input type="email" id="email" name="email" required value="<?php echo e(old('email')); ?>"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">パスワード</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            
            <div id="name_field">
                <label for="name" class="block text-sm font-medium text-gray-700">氏名</label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            
            <div id="kana_field">
                <label for="kana" class="block text-sm font-medium text-gray-700">フリガナ (カナ)</label>
                <input type="text" id="kana" name="kana" value="<?php echo e(old('kana')); ?>"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="例: ヤマダタロウ">
            </div>

            
            
            <div id="class_id_field" style="<?php echo e($selectedUserType === 'admin' ? 'display: none;' : 'display: block;'); ?>">
                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">所属クラス (学年/クラス)</label>
                <select id="class_id" name="class_id"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                    <option value="" disabled <?php echo e(old('class_id') == null ? 'selected' : ''); ?>>学年・クラスを選択してください</option>
                    
                    <?php if(!empty($classes)): ?>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id') == $class->id ? 'selected' : ''); ?>> <?php echo e($class->grade); ?>年 <?php echo e($class->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <option value="" disabled>クラス情報がありません</option>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    ユーザーを登録する
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 初期状態でユーザータイプに応じてフォームフィールドを切り替え
        toggleFields();
    });

    /**
     * ユーザータイプ（生徒/教師/管理者）に応じて、フォームフィールドの表示・非表示を切り替えます。
     * また、必須属性を動的に設定します。
     */
    function toggleFields() {
        const selectedUserType = document.getElementById('user_type').value;

        const nameInput = document.getElementById('name');
        const kanaInput = document.getElementById('kana');

        // 💡 存在しない grade_field, gradeInput の制御は削除しました。
        const classIdField = document.getElementById('class_id_field');
        const classIdInput = document.getElementById('class_id');

        // デフォルトで全ての必須属性をリセット
        nameInput.removeAttribute('required');
        kanaInput.removeAttribute('required');
        classIdInput.removeAttribute('required');
        // gradeInputの制御は削除

        if (selectedUserType === 'student' || selectedUserType === 'teacher') {
            // 生徒・教師の場合: 氏名、フリガナ、クラスを必須とする
            nameInput.setAttribute('required', 'required');
            kanaInput.setAttribute('required', 'required');
            classIdInput.setAttribute('required', 'required');

            // クラスIDフィールドを表示
            classIdField.style.display = 'block';

        } else {
            // admin の場合: 氏名、フリガナを必須とする
            nameInput.setAttribute('required', 'required');
            kanaInput.setAttribute('required', 'required');

            // クラスIDフィールドは非表示
            classIdField.style.display = 'none';
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/admins/admin_create_user.blade.php ENDPATH**/ ?>