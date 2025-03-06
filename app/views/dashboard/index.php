<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">لوحة التحكم</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- User Info Card -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">معلومات المستخدم</h2>
                <p>الاسم: <?php echo htmlspecialchars($user['name']); ?></p>
                <p>الدور: <?php echo htmlspecialchars($user['role']); ?></p>
            </div>

            <!-- Quick Stats -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">إحصائيات سريعة</h2>
                <p>سيتم إضافة الإحصائيات قريباً</p>
            </div>

            <!-- Recent Activity -->
            <div class="bg-purple-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-2">آخر النشاطات</h2>
                <p>سيتم إضافة النشاطات قريباً</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 