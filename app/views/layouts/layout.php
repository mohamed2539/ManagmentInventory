<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $this->url('auth/login'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المواد</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $this->asset('css/style.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="<?= $this->url('') ?>" class="text-white font-bold">نظام إدارة المواد</a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="<?= $this->url('materials') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                المواد
                            </a>
                            <a href="<?= $this->url('transactions') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                المعاملات
                            </a>
                            <a href="<?= $this->url('materials/search') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                البحث المباشر
                            </a>
                            <a href="<?= $this->url('materials/liveQuantity') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                تحديث الكمية المباشر
                            </a>
                            <a href="<?= $this->url('dashboard') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-tachometer-alt ml-2"></i>
                                لوحة التحكم
                            </a>
                            <a href="<?= $this->url('user') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-users ml-2"></i>
                                المستخدمين
                            </a>
                            <a href="<?= $this->url('branch') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-building ml-2"></i>
                                الفروع
                            </a>
                            <a href="<?= $this->url('supplier') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-truck ml-2"></i>
                                الموردين
                            </a>
                            <a href="<?= $this->url('category') ?>" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-tags ml-2"></i>
                                الأقسام
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center">
                            <span class="text-gray-300 mr-4"><?= $this->escape($_SESSION['full_name'] ?? '') ?></span>
                            <a href="<?= $this->url('auth/logout') ?>" 
                               class="text-red-500 hover:text-red-400">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?= $this->url('materials') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-box ml-2"></i>
                    المواد
                </a>
                <a href="<?= $this->url('transactions') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-exchange-alt ml-2"></i>
                    المعاملات
                </a>
                <a href="<?= $this->url('dashboard') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-tachometer-alt ml-2"></i>
                    لوحة التحكم
                </a>
                <a href="<?= $this->url('user') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-users ml-2"></i>
                    المستخدمين
                </a>
                <a href="<?= $this->url('branch') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-building ml-2"></i>
                    الفروع
                </a>
                <a href="<?= $this->url('supplier') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-truck ml-2"></i>
                    الموردين
                </a>
                <a href="<?= $this->url('category') ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-tags ml-2"></i>
                    الأقسام
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4">
            <div class="text-center text-gray-600">
                <p>جميع الحقوق محفوظة &copy; <?= date('Y') ?> نظام إدارة المواد</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script src="<?= $this->asset('js/main.js') ?>"></script>
</body>
</html> 