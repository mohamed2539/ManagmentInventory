<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تعديل المادة</h1>
        <a href="/NMaterailManegmentT/public/index.php?controller=material&action=index" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            عودة للقائمة
        </a>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form id="editMaterialForm" method="POST" 
              action="/NMaterailManegmentT/public/index.php?controller=material&action=update" 
              class="space-y-6">
            <input type="hidden" name="id" value="<?php echo $material['id']; ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">الكود</label>
                    <input type="text" name="code" required 
                           value="<?php echo htmlspecialchars($material['code']); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">اسم المادة</label>
                    <input type="text" name="name" required 
                           value="<?php echo htmlspecialchars($material['name']); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">الوصف</label>
                    <textarea name="description" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($material['description']); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">الكمية</label>
                    <input type="number" name="quantity" required min="0" step="0.01"
                           value="<?php echo htmlspecialchars($material['quantity']); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">الوحدة</label>
                    <input type="text" name="unit" required 
                           value="<?php echo htmlspecialchars($material['unit']); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">الفئة</label>
                    <select name="category_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">اختر الفئة</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo $category['id'] == $material['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">المورد</label>
                    <select name="supplier_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">اختر المورد</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?php echo $supplier['id']; ?>" 
                                    <?php echo $supplier['id'] == $material['supplier_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($supplier['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="/NMaterailManegmentT/public/index.php?controller=material&action=index" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?> 