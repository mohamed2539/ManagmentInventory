document.addEventListener('DOMContentLoaded', function() {
    const supplierForm = document.getElementById('supplierForm');
    const messageDiv = document.getElementById('message');
    const suppliersTable = document.getElementById('suppliersTable');
    const editModal = document.getElementById('editModal');
    let currentSupplierId = null;

    // Load suppliers on page load
    loadSuppliers();

    // Handle form submission for new supplier
    if (supplierForm) {
        supplierForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(supplierForm);
            
            try {
                const response = await fetch('/NMaterailManegmentT/public/index.php?controller=supplier&action=store', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showMessage(result.message, 'success');
                    supplierForm.reset();
                    loadSuppliers();
                } else {
                    showMessage(result.message, 'error');
                }
            } catch (error) {
                showMessage('حدث خطأ أثناء إضافة المورد', 'error');
            }
        });
    }

    // Handle supplier deletion
    async function deleteSupplier(id) {
        if (!confirm('هل أنت متأكد من حذف هذا المورد؟')) {
            return;
        }

        try {
            const response = await fetch(`/NMaterailManegmentT/public/index.php?controller=supplier&action=delete&id=${id}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                loadSuppliers();
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء حذف المورد', 'error');
        }
    }

    // Handle supplier update
    async function updateSupplier(id, formData) {
        try {
            formData.append('id', id);
            const response = await fetch('/NMaterailManegmentT/public/index.php?controller=supplier&action=update', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                closeEditModal();
                loadSuppliers();
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحديث بيانات المورد', 'error');
        }
    }

    // Load suppliers
    async function loadSuppliers() {
        try {
            const response = await fetch('/NMaterailManegmentT/public/index.php?controller=supplier&action=getSuppliers');
            const result = await response.json();
            
            if (result.status === 'success') {
                updateSuppliersTable(result.data);
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحميل الموردين', 'error');
        }
    }

    // Update suppliers table
    function updateSuppliersTable(suppliers) {
        if (!suppliersTable) return;

        const tbody = suppliersTable.querySelector('tbody');
        tbody.innerHTML = '';

        suppliers.forEach(supplier => {
            const row = document.createElement('tr');
            row.className = 'supplier-table-row';
            row.innerHTML = `
                <td class="supplier-table-cell">${supplier.name}</td>
                <td class="supplier-table-cell">${supplier.phone}</td>
                <td class="supplier-table-cell">${supplier.email || '-'}</td>
                <td class="supplier-table-cell">${supplier.contact_person || '-'}</td>
                <td class="supplier-table-cell">${supplier.address || '-'}</td>
                <td class="supplier-table-cell">
                    <button onclick="openEditModal(${supplier.id})" class="btn-edit">
                        تعديل
                    </button>
                    <button onclick="deleteSupplier(${supplier.id})" class="btn-delete">
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
        messageDiv.className = `message-${type}`;
        messageDiv.style.display = 'block';

        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    // Modal functions
    window.openEditModal = function(supplierId) {
        currentSupplierId = supplierId;
        editModal.style.display = 'block';
        loadSupplierData(supplierId);
    }

    window.closeEditModal = function() {
        editModal.style.display = 'none';
        currentSupplierId = null;
    }

    // Load supplier data for editing
    async function loadSupplierData(supplierId) {
        try {
            const response = await fetch(`/NMaterailManegmentT/public/index.php?controller=supplier&action=get&id=${supplierId}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                const supplier = result.data;
                document.getElementById('edit_name').value = supplier.name;
                document.getElementById('edit_phone').value = supplier.phone;
                document.getElementById('edit_email').value = supplier.email || '';
                document.getElementById('edit_contact_person').value = supplier.contact_person || '';
                document.getElementById('edit_address').value = supplier.address || '';
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحميل بيانات المورد', 'error');
        }
    }

    // Handle edit form submission
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!currentSupplierId) return;

            const formData = new FormData(editForm);
            await updateSupplier(currentSupplierId, formData);
        });
    }

    // Make functions available globally
    window.deleteSupplier = deleteSupplier;
}); 