<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">إضافة كمية للمواد</h1>
        <a href="/NMaterailManegmentT/public/index.php?controller=transaction&action=index" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            عودة للمعاملات
        </a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form id="additionForm" method="POST" 
              action="/NMaterailManegmentT/public/index.php?controller=transaction&action=addition" 
              class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">كود المادة</label>
                    <div class="mt-1 relative">
                        <input type="text" name="code" id="materialCode" required 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               onchange="getMaterialDetails(this.value)">
                    </div>
                </div>

                <div id="materialDetails" class="md:col-span-2 hidden">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">تفاصيل المادة</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-sm font-medium text-gray-500">اسم المادة:</span>
                                <span id="materialName" class="text-gray-900"></span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500">الكمية الحالية:</span>
                                <span id="materialQuantity" class="text-gray-900"></span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500">الوحدة:</span>
                                <span id="materialUnit" class="text-gray-900"></span>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-gray-500">الفئة:</span>
                                <span id="materialCategory" class="text-gray-900"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">الكمية المضافة</label>
                    <input type="number" name="quantity" required min="0.01" step="0.01"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                    <textarea name="notes" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="/NMaterailManegmentT/public/index.php?controller=transaction&action=index" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    إضافة الكمية
                </button>
            </div>
        </form>
    </div>
</div>

<script src="/NMaterailManegmentT/public/assets/js/transactions.js"></script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?> 