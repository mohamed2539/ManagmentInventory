<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تحديث الكمية المباشر</h1>
    </div>

    <div class="mb-6">
        <div class="relative">
            <input type="text" id="materialCodeInput" 
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="أدخل كود المادة..."
                   onchange="getMaterialDetails(this.value)">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-barcode text-gray-400"></i>
            </div>
        </div>
    </div>

    <div id="materialDetails" class="hidden bg-white shadow-md rounded-lg overflow-hidden p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">تفاصيل المادة</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">اسم المادة</label>
                <p id="materialName" class="mt-1 text-gray-900"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">الكمية الحالية</label>
                <p id="materialQuantity" class="mt-1 text-gray-900"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">الوحدة</label>
                <p id="materialUnit" class="mt-1 text-gray-900"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">الفئة</label>
                <p id="materialCategory" class="mt-1 text-gray-900"></p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">الكمية الجديدة</label>
                <input type="number" id="newQuantity" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       min="0" step="0.01">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="updateQuantity('addition')" 
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    إضافة كمية
                </button>
                <button onclick="updateQuantity('withdrawal')" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    صرف كمية
                </button>
            </div>
        </div>
    </div>

    <div id="transactionHistory" class="hidden bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-xl font-semibold p-6 border-b">سجل المعاملات</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع المعاملة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                </tr>
            </thead>
            <tbody id="transactionHistoryBody" class="bg-white divide-y divide-gray-200">
                <!-- سجل المعاملات سيظهر هنا -->
            </tbody>
        </table>
    </div>
</div>

<script>
let currentMaterialId = null;

function getMaterialDetails(code) {
    if (!code) {
        document.getElementById('materialDetails').classList.add('hidden');
        document.getElementById('transactionHistory').classList.add('hidden');
        currentMaterialId = null;
        return;
    }

    fetch(`/NMaterailManegmentT/public/index.php?controller=material&action=getByCode&code=${encodeURIComponent(code)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            currentMaterialId = data.data.id;
            document.getElementById('materialName').textContent = data.data.name;
            document.getElementById('materialQuantity').textContent = `${data.data.quantity} ${data.data.unit}`;
            document.getElementById('materialUnit').textContent = data.data.unit;
            document.getElementById('materialCategory').textContent = data.data.category_name;
            document.getElementById('materialDetails').classList.remove('hidden');
            
            // Get transaction history
            getTransactionHistory(currentMaterialId);
        } else {
            showError(data.message || "المادة غير موجودة");
            document.getElementById('materialDetails').classList.add('hidden');
            document.getElementById('transactionHistory').classList.add('hidden');
            currentMaterialId = null;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showError("حدث خطأ أثناء جلب بيانات المادة");
        document.getElementById('materialDetails').classList.add('hidden');
        document.getElementById('transactionHistory').classList.add('hidden');
        currentMaterialId = null;
    });
}

function getTransactionHistory(materialId) {
    fetch(`/NMaterailManegmentT/public/index.php?controller=transaction&action=getByMaterial&material_id=${materialId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const tbody = document.getElementById('transactionHistoryBody');
            tbody.innerHTML = data.data.map(transaction => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                   ${transaction.type === 'withdrawal' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                            ${transaction.type === 'withdrawal' ? 'صرف' : 'إضافة'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.quantity}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.user_name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.notes || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.created_at}</td>
                </tr>
            `).join('');
            document.getElementById('transactionHistory').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showError("حدث خطأ أثناء جلب سجل المعاملات");
    });
}

function updateQuantity(type) {
    if (!currentMaterialId) {
        showError("الرجاء اختيار مادة أولاً");
        return;
    }

    const quantity = document.getElementById('newQuantity').value;
    if (!quantity || quantity <= 0) {
        showError("الرجاء إدخال كمية صحيحة");
        return;
    }

    const formData = new FormData();
    formData.append('code', document.getElementById('materialCodeInput').value);
    formData.append('quantity', quantity);
    formData.append('notes', document.getElementById('notes').value);

    fetch(`/NMaterailManegmentT/public/index.php?controller=transaction&action=${type}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            showSuccess(data.message);
            document.getElementById('newQuantity').value = '';
            document.getElementById('notes').value = '';
            getMaterialDetails(document.getElementById('materialCodeInput').value);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showError("حدث خطأ أثناء تحديث الكمية");
    });
}

function showSuccess(message) {
    const alert = document.createElement('div');
    alert.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
    alert.role = 'alert';
    alert.innerHTML = `<span class="block sm:inline">${message}</span>`;
    
    const container = document.querySelector('.container');
    container.insertBefore(alert, document.getElementById('materialDetails'));
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

function showError(message) {
    const alert = document.createElement('div');
    alert.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
    alert.role = 'alert';
    alert.innerHTML = `<span class="block sm:inline">${message}</span>`;
    
    const container = document.querySelector('.container');
    container.insertBefore(alert, document.getElementById('materialDetails'));
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?> 