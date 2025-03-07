<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تعديل الفرع</h1>
        <a href="/NMaterailManegmentT/public/index.php?controller=branch&action=index" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            العودة للقائمة
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $error; ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="/NMaterailManegmentT/public/index.php?controller=branch&action=update" method="POST" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($branch['id']); ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700">اسم الفرع</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($branch['name']); ?>" required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($branch['address']); ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">الهاتف</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($branch['phone']); ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($branch['email']); ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">مدير الفرع</label>
                <input type="text" name="manager_name" value="<?php echo htmlspecialchars($branch['manager_name']); ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">الحالة</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" <?php echo $branch['status'] === 'active' ? 'selected' : ''; ?>>نشط</option>
                    <option value="inactive" <?php echo $branch['status'] === 'inactive' ? 'selected' : ''; ?>>غير نشط</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea name="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($branch['notes']); ?></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="/NMaterailManegmentT/public/index.php?controller=branch&action=index" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">إلغاء</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?> 