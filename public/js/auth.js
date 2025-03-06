document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const messageDiv = document.getElementById('message');

    // Handle login form submission
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            
            try {
                const response = await fetch('/NMaterailManegmentT/public/index.php?controller=auth&action=authenticate', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showMessage('تم تسجيل الدخول بنجاح', 'success');
                    window.location.href = '/NMaterailManegmentT/public/index.php?controller=dashboard';
                } else {
                    showMessage(result.message || 'فشل تسجيل الدخول', 'error');
                }
            } catch (error) {
                showMessage('حدث خطأ أثناء تسجيل الدخول', 'error');
            }
        });
    }

    // Handle registration form submission
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            
            try {
                const response = await fetch('/NMaterailManegmentT/public/index.php?controller=auth&action=store', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showMessage('تم إنشاء الحساب بنجاح', 'success');
                    registerForm.reset();
                } else {
                    showMessage(result.message || 'فشل إنشاء الحساب', 'error');
                }
            } catch (error) {
                showMessage('حدث خطأ أثناء إنشاء الحساب', 'error');
            }
        });
    }

    // Load branches for registration form
    async function loadBranches() {
        const branchSelect = document.getElementById('branch_id');
        if (!branchSelect) return;

        try {
            const response = await fetch('/NMaterailManegmentT/public/index.php?controller=branch&action=getBranches');
            const result = await response.json();
            
            if (result.status === 'success') {
                result.data.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });
            }
        } catch (error) {
            showMessage('حدث خطأ أثناء تحميل الفروع', 'error');
        }
    }

    // Show message function
    function showMessage(message, type) {
        if (!messageDiv) return;

        messageDiv.textContent = message;
        messageDiv.className = `auth-message ${type === 'success' ? 'auth-message-success' : 'auth-message-error'}`;
        messageDiv.style.display = 'block';

        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }

    // Load branches if on registration page
    if (registerForm) {
        loadBranches();
    }
}); 