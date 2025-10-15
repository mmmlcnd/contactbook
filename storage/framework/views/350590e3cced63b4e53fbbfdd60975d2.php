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
            <?php echo $__env->yieldContent('nav'); ?>
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