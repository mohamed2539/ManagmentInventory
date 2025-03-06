document.addEventListener('DOMContentLoaded', function() {
    const categoryForm = document.getElementById('categoryForm');
    const messageDiv = document.getElementById('message');
    const categoriesTable = document.getElementById('categoriesTable');
    const editModal = document.getElementById('editModal');
    let currentCategoryId = null;

    // Load categories on page load
    loadCategories();

    // Handle form submission for new category
    if (categoryForm) {
        categoryForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(categoryForm);
            
            try {
                const response = await fetch('/NMaterailManegmentT/public/index.php?controller=category&action=store', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showMessage(result.message, 'success');
                    categoryForm.reset();
                    loadCategories();
                } else {
                    showMessage(result.message, 'error');
                }
            } catch (error) {
                showMessage('حدث خطأ أثناء إضافة الفئة', 'error');
            }
        });
    }

    // Handle category deletion
    async function deleteCategory(id) {
        if (!confirm('هل أنت متأكد من حذف هذه الفئة؟')) {
            return;
        }

        try {
            const response = await fetch(`/NMaterailManegmentT/public/index.php?controller=category&action=delete&id=${id}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                loadCategories();
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء حذف الفئة', 'error');
        }
    }

    // Handle category update
    async function updateCategory(id, formData) {
        try {
            formData.append('id', id);
            const response = await fetch('/NMaterailManegmentT/public/index.php?controller=category&action=update', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                closeEditModal();
                loadCategories();
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحديث الفئة', 'error');
        }
    }

    // Load categories
    async function loadCategories() {
        try {
            const response = await fetch('/NMaterailManegmentT/public/index.php?controller=category&action=getCategories');
            const result = await response.json();
            
            if (result.status === 'success') {
                updateCategoriesTable(result.data);
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحميل الفئات', 'error');
        }
    }

    // Update categories table
    function updateCategoriesTable(categories) {
        if (!categoriesTable) return;

        const tbody = categoriesTable.querySelector('tbody');
        tbody.innerHTML = '';

        categories.forEach(category => {
            const row = document.createElement('tr');
            row.className = 'category-table-row';
            row.innerHTML = `
                <td class="table-cell">${category.name}</td>
                <td class="table-cell">${category.description}</td>
                <td class="table-cell">${category.items_count || 0}</td>
                <td class="table-cell">
                    <button onclick="openEditModal(${category.id})" class="btn-edit">
                        تعديل
                    </button>
                    <button onclick="deleteCategory(${category.id})" class="btn-delete">
                        حذف
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Show message
    function showMessage(message, type) {
        if (!messageDiv) return;

        messageDiv.textContent = message;
        messageDiv.className = type === 'success' ? 'message-success' : 'message-error';
        messageDiv.style.display = 'block';

        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    // Modal functions
    window.openEditModal = function(categoryId) {
        currentCategoryId = categoryId;
        editModal.style.display = 'block';
        loadCategoryData(categoryId);
    }

    window.closeEditModal = function() {
        editModal.style.display = 'none';
        currentCategoryId = null;
    }

    // Load category data for editing
    async function loadCategoryData(categoryId) {
        try {
            const response = await fetch(`/NMaterailManegmentT/public/index.php?controller=category&action=get&id=${categoryId}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                const category = result.data;
                document.getElementById('edit_name').value = category.name;
                document.getElementById('edit_description').value = category.description;
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحميل بيانات الفئة', 'error');
        }
    }

    // Make functions available globally
    window.deleteCategory = deleteCategory;
}); 