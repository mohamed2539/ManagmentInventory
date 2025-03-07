<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">مرحباً بك في نظام إدارة المواد</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- بطاقة الفروع -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">الفروع</h2>
                <p class="text-gray-600 mb-4">إدارة الفروع وإضافة فروع جديدة</p>
                <a href="<?= $this->url('branch') ?>" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    عرض الفروع
                </a>
            </div>

            <!-- بطاقة المواد -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">المواد</h2>
                <p class="text-gray-600 mb-4">إدارة المواد وإضافة مواد جديدة</p>
                <a href="<?= $this->url('materials') ?>" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    عرض المواد
                </a>
            </div>

            <!-- بطاقة الموردين -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">الموردين</h2>
                <p class="text-gray-600 mb-4">إدارة الموردين وإضافة موردين جدد</p>
                <a href="<?= $this->url('supplier') ?>" class="inline-block bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                    عرض الموردين
                </a>
            </div>
        </div>
    </div>
</div> 