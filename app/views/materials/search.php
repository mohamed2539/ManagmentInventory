<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">البحث المباشر في المواد</h1>
    </div>

    <div class="mb-6">
        <div class="relative">
            <input type="text" id="liveSearchInput" 
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="ابدأ الكتابة للبحث في المواد (الكود، الاسم، الوصف)..."
                   onkeyup="liveSearch(this.value)">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </div>
    </div>

    <div id="searchResults" class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المادة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوحدة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المورد</th>
                </tr>
            </thead>
            <tbody id="searchResultsBody" class="bg-white divide-y divide-gray-200">
                <!-- النتائج ستظهر هنا -->
            </tbody>
        </table>
    </div>
</div>

<script>
let searchTimeout = null;

function liveSearch(term) {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
        if (term.length >= 2) {
            fetch(`/NMaterailManegmentT/public/index.php?controller=material&action=search&term=${encodeURIComponent(term)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.querySelector('#materialsTableBody');
                if (newTableBody) {
                    document.getElementById('searchResultsBody').innerHTML = newTableBody.innerHTML;
                }
            })
            .catch(error => {
                console.error("Error searching materials:", error);
                showError("حدث خطأ أثناء البحث");
            });
        } else {
            document.getElementById('searchResultsBody').innerHTML = '';
        }
    }, 300);
}

function showError(message) {
    const alert = document.createElement('div');
    alert.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
    alert.role = 'alert';
    alert.innerHTML = `<span class="block sm:inline">${message}</span>`;
    
    const container = document.querySelector('.container');
    container.insertBefore(alert, document.getElementById('searchResults'));
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?> 