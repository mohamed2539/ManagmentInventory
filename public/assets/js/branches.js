document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const createModal = document.getElementById("createModal");
    const editModal = document.getElementById("editModal");
    const createForm = document.getElementById("createBranchForm");
    const editForm = document.getElementById("editBranchForm");

    // Only add event listeners if the elements exist
    if (createForm) {
        createForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch("/NMaterailManegmentT/public/index.php?controller=branch&action=create", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert(data.message);
                        closeCreateModal();
                        location.reload();
                    } else {
                        alert("خطأ: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("حدث خطأ أثناء إضافة الفرع");
                });
        });
    }

    if (editForm) {
        editForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            
            // Convert status value to 'active' or 'inactive' before sending
            const statusSelect = document.getElementById("editStatus");
            formData.set('status', statusSelect.value === '1' ? 'active' : 'inactive');

            fetch("/NMaterailManegmentT/public/index.php?controller=branch&action=update", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert(data.message);
                        closeEditModal();
                        location.reload();
                    } else {
                        alert("خطأ: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("حدث خطأ أثناء تحديث الفرع");
                });
        });
    }

    // Create Modal Functions
    window.openCreateModal = function() {
        if (createModal) {
            createModal.classList.remove("hidden");
        }
    };

    window.closeCreateModal = function() {
        if (createModal) {
            createModal.classList.add("hidden");
        }
    };

    // Edit Modal Functions
    window.openEditModal = function(branchId) {
        console.log('Opening edit modal for branch:', branchId);
        fetch(`/NMaterailManegmentT/public/index.php?controller=branch&action=edit&id=${branchId}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.status === "success" && data.branch) {
                    const branch = data.branch;
                    console.log('Branch data:', branch);
                    
                    // Set form values
                    const formFields = {
                        'editBranchId': branch.id,
                        'editName': branch.name,
                        'editAddress': branch.address || '',
                        'editPhone': branch.phone || '',
                        'editEmail': branch.email || '',
                        'editManagerName': branch.manager_name || '',
                        'editStatus': branch.status === 'active' ? '1' : '0',
                        'editNotes': branch.notes || ''
                    };

                    // Set each field value
                    Object.entries(formFields).forEach(([fieldId, value]) => {
                        const element = document.getElementById(fieldId);
                        if (element) {
                            element.value = value;
                        } else {
                            console.error(`Element not found: ${fieldId}`);
                        }
                    });

                    // Show modal
                    if (editModal) {
                        editModal.classList.remove("hidden");
                    }
                } else {
                    console.error('Invalid response format:', data);
                    alert("خطأ: " + (data.message || "حدث خطأ أثناء تحميل البيانات"));
                }
            })
            .catch(error => {
                console.error("Error details:", error);
                alert("حدث خطأ أثناء تحميل البيانات: " + error.message);
            });
    };

    window.closeEditModal = function() {
        if (editModal) {
            editModal.classList.add("hidden");
        }
    };

    // Delete Branch
    window.deleteBranch = function(branchId) {
        if (confirm("هل أنت متأكد من حذف هذا الفرع؟")) {
            fetch(`/NMaterailManegmentT/public/index.php?controller=branch&action=delete&id=${branchId}`, {
                method: "GET"
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert("خطأ: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("حدث خطأ أثناء حذف الفرع");
                });
        }
    };

    // Close modals when clicking outside
    if (createModal) {
        createModal.addEventListener("click", function(event) {
            if (event.target === createModal) {
                closeCreateModal();
            }
        });
    }

    if (editModal) {
        editModal.addEventListener("click", function(event) {
            if (event.target === editModal) {
                closeEditModal();
            }
        });
    }
}); 