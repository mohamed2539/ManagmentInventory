<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ في الخادم - نظام إدارة المواد</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl text-red-500 mb-4">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">خطأ في الخادم</h1>
            <p class="text-gray-600 mb-6">عذراً، حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى لاحقاً.</p>
            <?php if (defined('DEBUG') && DEBUG && isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-right mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <a href="/NMaterailManegmentT/public/" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>
</body>
</html> 