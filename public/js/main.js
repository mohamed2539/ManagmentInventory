// Common JavaScript functions
document.addEventListener('DOMContentLoaded', function() {
    // Handle mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Handle form submissions
    const forms = document.querySelectorAll('form[data-ajax="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: this.method,
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    alert(result.message);
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    }
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ البيانات');
            }
        });
    });
});

// Base URL for the application
const BASE_URL = '/NMaterailManegmentT/public';

// Helper function to generate API URLs
function apiUrl(controller, action = 'index', params = {}) {
    let url = `${BASE_URL}/api/${controller}/${action}`;
    if (Object.keys(params).length > 0) {
        url += '?' + new URLSearchParams(params).toString();
    }
    return url;
}

// Helper function to generate page URLs
function url(path = '') {
    return `${BASE_URL}/${path}`.replace(/\/+/g, '/');
}

// Helper function to generate asset URLs
function asset(path = '') {
    return `${BASE_URL}/assets/${path}`.replace(/\/+/g, '/');
}

// Helper function for making API calls
async function apiCall(controller, action = 'index', method = 'GET', data = null, params = {}) {
    const url = apiUrl(controller, action, params);
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    if (data && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('API call error:', error);
        throw error;
    }
}

// Show success message
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'نجاح',
        text: message,
        confirmButtonText: 'حسناً'
    });
}

// Show error message
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'خطأ',
        text: message,
        confirmButtonText: 'حسناً'
    });
}

// Show confirmation dialog
function showConfirm(message) {
    return Swal.fire({
        icon: 'warning',
        title: 'تأكيد',
        text: message,
        showCancelButton: true,
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    });
}

// Export helpers
window.apiUrl = apiUrl;
window.url = url;
window.asset = asset;
window.apiCall = apiCall;
window.showSuccess = showSuccess;
window.showError = showError;
window.showConfirm = showConfirm; 