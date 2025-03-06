<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموردين - نظام إدارة المواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/style.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/suppliers.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">إدارة الموردين</h1>

        <!-- Message Display -->
        <div id="message" class="hidden"></div>

        <!-- Add Supplier Form -->
        <div class="supplier-card">
            <h2 class="supplier-title">إضافة مورد جديد</h2>
            <form id="supplierForm" class="supplier-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="supplier-form-group">
                        <label for="name" class="supplier-label">اسم المورد</label>
                        <input type="text" id="name" name="name" required class="supplier-input" 
                               placeholder="أدخل اسم المورد">
                    </div>

                    <div class="supplier-form-group">
                        <label for="phone" class="supplier-label">رقم الهاتف</label>
                        <input type="tel" id="phone" name="phone" required class="supplier-input"
                               placeholder="أدخل رقم الهاتف">
                    </div>

                    <div class="supplier-form-group">
                        <label for="email" class="supplier-label">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="supplier-input"
                               placeholder="أدخل البريد الإلكتروني">
                    </div>

                    <div class="supplier-form-group">
                        <label for="contact_person" class="supplier-label">الشخص المسؤول</label>
                        <input type="text" id="contact_person" name="contact_person" class="supplier-input"
                               placeholder="أدخل اسم الشخص المسؤول">
                    </div>

                    <div class="supplier-form-group md:col-span-2">
                        <label for="address" class="supplier-label">العنوان</label>
                        <textarea id="address" name="address" class="supplier-input"
                                  placeholder="أدخل عنوان المورد"></textarea>
                    </div>
                </div>

                <div class="supplier-action-buttons">
                    <button type="submit" class="supplier-btn-primary">إضافة المورد</button>
                </div>
            </form>
        </div>

        <!-- Suppliers Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
            <table id="suppliersTable" class="supplier-table">
                <thead class="supplier-table-header">
                    <tr>
                        <th class="supplier-table-cell">اسم المورد</th>
                        <th class="supplier-table-cell">رقم الهاتف</th>
                        <th class="supplier-table-cell">البريد الإلكتروني</th>
                        <th class="supplier-table-cell">الشخص المسؤول</th>
                        <th class="supplier-table-cell">العنوان</th>
                        <th class="supplier-table-cell">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Suppliers will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay hidden">
        <div class="supplier-modal-content">
            <h3 class="supplier-modal-header">تعديل المورد</h3>
            <form id="editForm" class="supplier-form">
                <div class="supplier-form-group">
                    <label for="edit_name" class="supplier-label">اسم المورد</label>
                    <input type="text" id="edit_name" name="name" required class="supplier-input">
                </div>

                <div class="supplier-form-group">
                    <label for="edit_phone" class="supplier-label">رقم الهاتف</label>
                    <input type="tel" id="edit_phone" name="phone" required class="supplier-input">
                </div>

                <div class="supplier-form-group">
                    <label for="edit_email" class="supplier-label">البريد الإلكتروني</label>
                    <input type="email" id="edit_email" name="email" class="supplier-input">
                </div>

                <div class="supplier-form-group">
                    <label for="edit_contact_person" class="supplier-label">الشخص المسؤول</label>
                    <input type="text" id="edit_contact_person" name="contact_person" class="supplier-input">
                </div>

                <div class="supplier-form-group">
                    <label for="edit_address" class="supplier-label">العنوان</label>
                    <textarea id="edit_address" name="address" class="supplier-input"></textarea>
                </div>

                <div class="supplier-action-buttons">
                    <button type="button" onclick="closeEditModal()" class="supplier-btn-secondary">إلغاء</button>
                    <button type="submit" class="supplier-btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/NMaterailManegmentT/public/js/suppliers.js"></script>
</body>
</html> 