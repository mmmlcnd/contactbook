<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>連絡帳管理システム</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    
    <header class="bg-blue-600 text-white shadow">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center p-4">
            <h1 class="text-xl font-semibold mb-2 md:mb-0">連絡帳管理システム</h1>

            
            <?php
            // Authから渡される変数 $userType を使用
            $role = $userType ?? ($_SESSION['user_type'] ?? null);
            ?>

            <nav class="flex space-x-4">
                <?php if($role): ?>
                <a href="/" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center bg-indigo-700 font-bold">
                    <i class="fas fa-school mr-1"></i> トップページ
                </a>
                <?php endif; ?>
                <?php if($role == 'admin'): ?>
                
                <a href="/admins/dashboard" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-chart-line mr-1"></i> ダッシュボード
                </a>
                <a href="/admins/users/create" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-users-cog mr-1"></i> ユーザー管理
                </a>
                <!-- <a href="/admins/classes/manage" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-chalkboard mr-1"></i> クラス管理
                </a> -->
                <?php elseif($role == 'teacher'): ?>
                
                <a href="/teachers/dashboard" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-home mr-1"></i> ダッシュボード
                </a>
                <a href="/teachers/status" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-bell mr-1"></i> 提出確認
                </a>
                <?php elseif($role == 'student'): ?>
                
                <a href="/students/dashboard" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-home mr-1"></i> ダッシュボード
                </a>
                <a href="/students/entries/create" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-pencil-alt mr-1"></i> 連絡帳提出
                </a>
                <a href="/students/entries/past" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-history mr-1"></i> 提出履歴
                </a>
                <?php endif; ?>

                
                <?php if($role): ?>
                <a href="/logout" class="bg-red-500 hover:bg-red-600 p-2 rounded transition duration-150 flex items-center font-bold">
                    <i class="fas fa-sign-out-alt mr-1"></i> ログアウト
                </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    
    <main class="container mx-auto flex-1 p-6 mt-6">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="bg-gray-700 text-white text-center py-3 text-sm mt-auto">
        &copy; 2025 架空私立中学校 連絡帳システム
    </footer>

</body>

</html><?php /**PATH C:\xampp\htdocs\contactbook\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>