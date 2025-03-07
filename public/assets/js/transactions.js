document.addEventListener("DOMContentLoaded", function () {
    // Get form elements
    const withdrawalForm = document.getElementById("withdrawalForm");
    const additionForm = document.getElementById("additionForm");

    // Form Submission
    if (withdrawalForm) {
        withdrawalForm.addEventListener("submit", function (event) {
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
                    this.reset();
                    document.getElementById('materialDetails').classList.add('hidden');
                } else {
                    showError(data.message || "حدث خطأ أثناء صرف المادة");
                }
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error("Error:", error);
                showError("حدث خطأ أثناء صرف المادة");
                submitButton.disabled = false;
            });
        });
    }

    if (additionForm) {
        additionForm.addEventListener("submit", function (event) {
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
                    this.reset();
                    document.getElementById('materialDetails').classList.add('hidden');
                } else {
                    showError(data.message || "حدث خطأ أثناء إضافة الكمية");
                }
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error("Error:", error);
                showError("حدث خطأ أثناء إضافة الكمية");
                submitButton.disabled = false;
            });
        });
    }

    // Get Material Details
    window.getMaterialDetails = function(code) {
        if (!code) {
            document.getElementById('materialDetails').classList.add('hidden');
            return;
        }

        fetch(`/NMaterailManegmentT/public/index.php?controller=material&action=getByCode&code=${encodeURIComponent(code)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === "success") {
                document.getElementById('materialName').textContent = data.data.name;
                document.getElementById('materialQuantity').textContent = `${data.data.quantity} ${data.data.unit}`;
                document.getElementById('materialUnit').textContent = data.data.unit;
                document.getElementById('materialCategory').textContent = data.data.category_name;
                document.getElementById('materialDetails').classList.remove('hidden');
            } else {
                showError(data.message || "المادة غير موجودة");
                document.getElementById('materialDetails').classList.add('hidden');
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError("حدث خطأ أثناء جلب بيانات المادة");
            document.getElementById('materialDetails').classList.add('hidden');
        });
    };

    // Search Transactions
    window.searchTransactions = function(term) {
        fetch(`/NMaterailManegmentT/public/index.php?controller=transaction&action=search&term=${encodeURIComponent(term)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.querySelector('#transactionsTableBody');
            if (newTableBody) {
                const currentTableBody = document.querySelector('#transactionsTableBody');
                currentTableBody.innerHTML = newTableBody.innerHTML;
            }
        })
        .catch(error => {
            console.error("Error searching transactions:", error);
            showError("حدث خطأ أثناء البحث");
        });
    };

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
}); 