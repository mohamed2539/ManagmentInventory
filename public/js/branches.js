// تحميل الفروع عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initializeEventListeners();
    
    // Initialize datatable if present
    initializeDataTable();
});

function initializeEventListeners() {
    // Add Branch Form
    const addBranchForm = document.getElementById('addBranchForm');
    if (addBranchForm) {
        addBranchForm.addEventListener('submit', handleAddBranch);
    }

    // Edit Branch Form
    const editBranchForm = document.getElementById('editBranchForm');
    if (editBranchForm) {
        editBranchForm.addEventListener('submit', handleEditBranch);
    }

    // Delete Branch Buttons
    const deleteBtns = document.querySelectorAll('.delete-branch-btn');
    deleteBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', handleDeleteBranch);
        }
    });

    // Search functionality
    const searchInput = document.getElementById('searchBranch');
    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }
}

function initializeDataTable() {
    const branchTable = document.getElementById('branchTable');
    if (branchTable) {
        $(branchTable).DataTable({
            responsive: true,
            order: [[0, 'desc']],
            language: {
                search: "بحث:",
                lengthMenu: "عرض _MENU_ سجلات",
                info: "عرض _START_ إلى _END_ من _TOTAL_ سجل",
                paginate: {
                    first: "الأول",
                    last: "الأخير",
                    next: "التالي",
                    previous: "السابق"
                }
            }
        });
    }
}

async function handleAddBranch(e) {
    e.preventDefault();
    try {
        const formData = new FormData(e.target);
        const response = await fetch('/NMaterailManegmentT/public/branches/add', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('success', 'تم إضافة الفرع بنجاح');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showAlert('error', result.message || 'حدث خطأ أثناء إضافة الفرع');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ في النظام');
    }
}

async function handleEditBranch(e) {
    e.preventDefault();
    try {
        const formData = new FormData(e.target);
        const branchId = e.target.dataset.branchId;
        
        const response = await fetch(`/NMaterailManegmentT/public/branches/edit/${branchId}`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('success', 'تم تحديث الفرع بنجاح');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showAlert('error', result.message || 'حدث خطأ أثناء تحديث الفرع');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ في النظام');
    }
}

async function handleDeleteBranch(e) {
    if (!confirm('هل أنت متأكد من حذف هذا الفرع؟')) {
        return;
    }
    
    try {
        const branchId = e.target.dataset.branchId;
        const response = await fetch(`/NMaterailManegmentT/public/branches/delete/${branchId}`, {
            method: 'POST'
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('success', 'تم حذف الفرع بنجاح');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showAlert('error', result.message || 'حدث خطأ أثناء حذف الفرع');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ في النظام');
    }
}

function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    const table = document.getElementById('branchTable');
    if (!table) return;

    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let cell of cells) {
            if (cell.textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto dismiss after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}

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
/*function openEditModal(branchId) {
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
}*/

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