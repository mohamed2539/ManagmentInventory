<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">الموردين</h1>
        <button onclick="document.getElementById('createSupplierModal').classList.remove('hidden')" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus ml-2"></i>إضافة مورد جديد
        </button>
    </div>

    <!-- جدول الموردين -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table id="suppliersTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرقم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العنوان</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- سيتم ملء هذا القسم بواسطة JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- نافذة إضافة مورد جديد -->
<div id="createSupplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">إضافة مورد جديد</h3>
            <form id="createSupplierForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="supplierName">
                        اسم المورد
                    </label>
                    <input type="text" id="supplierName" name="name" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="supplierAddress">
                        العنوان
                    </label>
                    <input type="text" id="supplierAddress" name="address" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="supplierPhone">
                        رقم الهاتف
                    </label>
                    <input type="text" id="supplierPhone" name="phone" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        حفظ
                    </button>
                    <button type="button"
                            onclick="document.getElementById('createSupplierModal').classList.add('hidden')"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- نافذة تعديل المورد -->
<div id="editSupplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">تعديل المورد</h3>
            <form id="editSupplierForm">
                <input type="hidden" id="editSupplierId" name="id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="editSupplierName">
                        اسم المورد
                    </label>
                    <input type="text" id="editSupplierName" name="name" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="editSupplierAddress">
                        العنوان
                    </label>
                    <input type="text" id="editSupplierAddress" name="address" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="editSupplierPhone">
                        رقم الهاتف
                    </label>
                    <input type="text" id="editSupplierPhone" name="phone" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        حفظ التغييرات
                    </button>
                    <button type="button"
                            onclick="document.getElementById('editSupplierModal').classList.add('hidden')"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= $this->asset('js/suppliers.js') ?>"></script> 