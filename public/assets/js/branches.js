document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const createModal = document.getElementById("createModal");
    const createForm = document.getElementById("createBranchForm");

    // Create Modal Functions
    window.openCreateModal = function() {
        if (createModal) {
            createModal.classList.remove("hidden");
            // Reset form when opening modal
            createForm && createForm.reset();
        }
    };

    window.closeCreateModal = function() {
        if (createModal) {
            createModal.classList.add("hidden");
        }
    };

    // Create Form Submission
    if (createForm) {
        createForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            // Disable submit button
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            fetch(this.action, {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === "success") {
                    showSuccess(data.message);
                    closeCreateModal();
                    updateBranchesTable();
                } else {
                    showError(data.message || "حدث خطأ أثناء إضافة الفرع");
                }
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error("Error:", error);
                showError("حدث خطأ أثناء إضافة الفرع");
                submitButton.disabled = false;
            });
        });
    }

    // Delete Branch
    window.deleteBranch = function(branchId) {
        if (confirm("هل أنت متأكد من حذف هذا الفرع؟")) {
            const formData = new FormData();
            formData.append('id', branchId);

            fetch("/NMaterailManegmentT/public/index.php?controller=branch&action=delete", {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === "success") {
                    showSuccess(data.message);
                    updateBranchesTable();
                } else {
                    showError(data.message || "حدث خطأ أثناء حذف الفرع");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showError("حدث خطأ أثناء حذف الفرع");
            });
        }
    };

    // Function to update branches table
    function updateBranchesTable() {
        fetch("/NMaterailManegmentT/public/index.php?controller=branch&action=index", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.querySelector('#branchesTableBody');
            if (newTableBody) {
                const currentTableBody = document.querySelector('#branchesTableBody');
                currentTableBody.innerHTML = newTableBody.innerHTML;
            }
        })
        .catch(error => {
            console.error("Error updating table:", error);
            showError("حدث خطأ أثناء تحديث البيانات");
        });
    }

    // Close modal when clicking outside
    if (createModal) {
        createModal.addEventListener("click", function(event) {
            if (event.target === createModal) {
                closeCreateModal();
            }
        });
    }
});

// Show success message
function showSuccess(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
    alertDiv.innerHTML = `
        <span class="block sm:inline">${message}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.bg-white'));
    setTimeout(() => alertDiv.remove(), 3000);
}

// Show error message
function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
    alertDiv.innerHTML = `
        <span class="block sm:inline">${message}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.bg-white'));
    setTimeout(() => alertDiv.remove(), 3000);
} 