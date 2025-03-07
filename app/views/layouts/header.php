<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المواد</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/NMaterailManegmentT/public/css/style.css">
    
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
                        <span class="text-white font-bold">نظام إدارة المواد</span>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="/NMaterailManegmentT/public/index.php?controller=home&action=index" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                الرئيسية
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=material&action=index" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                المواد
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=transaction&action=index" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                المعاملات
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=material&action=search" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                البحث المباشر
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=material&action=liveQuantity" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                تحديث الكمية المباشر
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=dashboard" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-tachometer-alt ml-2"></i>
                                لوحة التحكم
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=user&action=index" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-users ml-2"></i>
                                المستخدمين
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=branch&action=index" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-building ml-2"></i>
                                الفروع
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=supplier&action=index" 
                               class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-truck ml-2"></i>
                                الموردين
                            </a>
                            <a href="/NMaterailManegmentT/public/index.php?controller=category&action=index" 
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
                            <span class="text-gray-700 mr-4"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span>
                            <a href="/NMaterailManegmentT/public/index.php?controller=auth&action=logout" 
                               class="text-red-600 hover:text-red-800">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="/NMaterailManegmentT/public/index.php?controller=dashboard" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-tachometer-alt ml-2"></i>
                    لوحة التحكم
                </a>

                <a href="/NMaterailManegmentT/public/index.php?controller=users" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-users ml-2"></i>
                    المستخدمين
                </a>

                <a href="/NMaterailManegmentT/public/index.php?controller=branch" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-building ml-2"></i>
                    الفروع
                </a>

                <a href="/NMaterailManegmentT/public/index.php?controller=suppliers" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-truck ml-2"></i>
                    الموردين
                </a>

                <a href="/NMaterailManegmentT/public/index.php?controller=categories" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-tags ml-2"></i>
                    الأقسام
                </a>

                <a href="/NMaterailManegmentT/public/index.php?controller=material&action=index" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-box ml-2"></i>
                    المواد
                </a>

                <a href="/NMaterailManegmentT/public/index.php?controller=transaction&action=index" 
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                    <i class="fas fa-exchange-alt ml-2"></i>
                    المعاملات
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main> 
    <!-- Custom JavaScript -->
    <script src="/NMaterailManegmentT/public/js/main.js"></script>
    <script src="/NMaterailManegmentT/public/js/branches.js"></script>
</body>
</html> 