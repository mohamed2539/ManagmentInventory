// تحميل الفروع عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadBranches();
});

// تحميل الفروع
function loadBranches() {
    fetch('/NMaterailManegmentT/public/index.php?controller=branch&action=index')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#branchesTable tbody');
                tbody.innerHTML = '';
                
                data.branches.forEach(branch => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${branch.name}</td>
                        <td>${branch.address}</td>
                        <td>${branch.phone}</td>
                        <td>${branch.email}</td>
                        <td>${branch.manager_name}</td>
                        <td>${branch.status === 1 ? 'نشط' : 'غير نشط'}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="openEditModal(${branch.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBranch(${branch.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
                } else {
                showError('خطأ في تحميل الفروع');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('حدث خطأ أثناء تحميل الفروع');
        });
}

// فتح نافذة إضافة فرع جديد
function openCreateModal() {
    document.getElementById('createModal').style.display = 'block';
}

// فتح نافذة تعديل فرع
function openEditModal(branchId) {
    fetch(`/NMaterailManegmentT/public/index.php?controller=branch&action=edit&id=${branchId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const branch = data.branch;
                document.getElementById('editBranchId').value = branch.id;
                document.getElementById('editName').value = branch.name;
                document.getElementById('editAddress').value = branch.address;
                document.getElementById('editPhone').value = branch.phone;
                document.getElementById('editEmail').value = branch.email;
                document.getElementById('editManagerName').value = branch.manager_name;
                document.getElementById('editStatus').value = branch.status;
                document.getElementById('editNotes').value = branch.notes;
                document.getElementById('editModal').style.display = 'block';
            } else {
                showError('خطأ في تحميل بيانات الفرع');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('حدث خطأ أثناء تحميل بيانات الفرع');
        });
}

// إغلاق النوافذ المنبثقة
function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// إغلاق النوافذ المنبثقة عند النقر خارجها
window.onclick = function(event) {
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target == createModal) {
        createModal.style.display = 'none';
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
}

// معالجة إضافة فرع جديد
document.getElementById('createBranchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/NMaterailManegmentT/public/index.php?controller=branch&action=create', {
                method: 'POST',
                body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('تم إضافة الفرع بنجاح');
            closeCreateModal();
            loadBranches();
        } else {
            showError(data.message || 'خطأ في إضافة الفرع');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('حدث خطأ أثناء إضافة الفرع');
    });
});

// معالجة تعديل فرع
document.getElementById('editBranchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/NMaterailManegmentT/public/index.php?controller=branch&action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('تم تحديث الفرع بنجاح');
                closeEditModal();
                loadBranches();
            } else {
            showError(data.message || 'خطأ في تحديث الفرع');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('حدث خطأ أثناء تحديث الفرع');
    });
});

// حذف فرع
function deleteBranch(branchId) {
    if (confirm('هل أنت متأكد من حذف هذا الفرع؟')) {
        fetch(`/NMaterailManegmentT/public/index.php?controller=branch&action=delete&id=${branchId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('تم حذف الفرع بنجاح');
                loadBranches();
            } else {
                showError(data.message || 'خطأ في حذف الفرع');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('حدث خطأ أثناء حذف الفرع');
        });
    }
}

// عرض رسالة نجاح
function showSuccess(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.card'));
    setTimeout(() => alertDiv.remove(), 3000);
}

// عرض رسالة خطأ
function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.card'));
    setTimeout(() => alertDiv.remove(), 3000);
} 