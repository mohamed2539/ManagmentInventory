document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const createModal = document.getElementById("createModal");
    const createForm = document.getElementById("createMaterialForm");

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
                    updateMaterialsTable();
                } else {
                    showError(data.message || "حدث خطأ أثناء إضافة المادة");
                }
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error("Error:", error);
                showError("حدث خطأ أثناء إضافة المادة");
                submitButton.disabled = false;
            });
        });
    }

    // Delete Material
    window.deleteMaterial = function(materialId) {
        if (confirm("هل أنت متأكد من حذف هذه المادة؟")) {
            const formData = new FormData();
            formData.append('id', materialId);

            fetch("/NMaterailManegmentT/public/index.php?controller=material&action=delete", {
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
                    updateMaterialsTable();
                } else {
                    showError(data.message || "حدث خطأ أثناء حذف المادة");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showError("حدث خطأ أثناء حذف المادة");
            });
        }
    };

    // Search Materials
    window.searchMaterials = function(term) {
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
                const currentTableBody = document.querySelector('#materialsTableBody');
                currentTableBody.innerHTML = newTableBody.innerHTML;
            }
        })
        .catch(error => {
            console.error("Error searching materials:", error);
            showError("حدث خطأ أثناء البحث");
        });
    };

    // Function to update materials table
    function updateMaterialsTable() {
        fetch("/NMaterailManegmentT/public/index.php?controller=material&action=index", {
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
                const currentTableBody = document.querySelector('#materialsTableBody');
                currentTableBody.innerHTML = newTableBody.innerHTML;
            }
        })
        .catch(error => {
            console.error("Error updating table:", error);
            showError("حدث خطأ أثناء تحديث البيانات");
        });
    }

    // Success Message
    window.showSuccess = function(message) {
        const alert = document.createElement('div');
        alert.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
        alert.role = 'alert';
        alert.innerHTML = `<span class="block sm:inline">${message}</span>`;
        
        const container = document.querySelector('.container');
        container.insertBefore(alert, container.firstChild);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    };

    // Error Message
    window.showError = function(message) {
        const alert = document.createElement('div');
        alert.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
        alert.role = 'alert';
        alert.innerHTML = `<span class="block sm:inline">${message}</span>`;
        
        const container = document.querySelector('.container');
        container.insertBefore(alert, container.firstChild);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    };

    // Close modal when clicking outside
    if (createModal) {
        createModal.addEventListener("click", function(event) {
            if (event.target === createModal) {
                closeCreateModal();
            }
        });
    }
}); 