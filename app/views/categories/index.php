<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفئات - نظام إدارة المواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/style.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/categories.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">إدارة الفئات</h1>

        <!-- Message Display -->
        <div id="message" class="hidden"></div>

        <!-- Add Category Form -->
        <div class="category-card">
            <h2 class="category-title">إضافة فئة جديدة</h2>
            <form id="categoryForm" class="category-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="category-form-group">
                        <label for="name" class="form-label">اسم الفئة</label>
                        <input type="text" id="name" name="name" required class="form-input">
                    </div>
                    <div class="category-form-group">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea id="description" name="description" required class="form-input"></textarea>
                    </div>
                </div>
                <div class="category-action-buttons">
                    <button type="submit" class="btn-primary">إضافة الفئة</button>
                </div>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
            <table id="categoriesTable" class="category-table">
                <thead class="category-table-header">
                    <tr>
                        <th class="table-header">اسم الفئة</th>
                        <th class="table-header">الوصف</th>
                        <th class="table-header">عدد المواد</th>
                        <th class="table-header">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Categories will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay hidden">
        <div class="category-modal-content">
            <h3 class="category-modal-header">تعديل الفئة</h3>
            <form id="editForm" class="category-form">
                <div class="category-form-group">
                    <label for="edit_name" class="form-label">اسم الفئة</label>
                    <input type="text" id="edit_name" name="name" required class="form-input">
                </div>
                <div class="category-form-group">
                    <label for="edit_description" class="form-label">الوصف</label>
                    <textarea id="edit_description" name="description" required class="form-input"></textarea>
                </div>
                <div class="category-action-buttons">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary">إلغاء</button>
                    <button type="submit" class="btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/NMaterailManegmentT/public/js/categories.js"></script>
</body>
</html> 