<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - نظام إدارة المواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/style.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-header">إنشاء حساب جديد</h1>
            <p class="auth-subheader">أدخل بيانات المستخدم الجديد</p>

            <!-- Message Display -->
            <div id="message" class="hidden"></div>

            <!-- Registration Form -->
            <form id="registerForm" class="auth-form">
                <div class="auth-form-group">
                    <label for="username" class="auth-label">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required class="auth-input" 
                           placeholder="أدخل اسم المستخدم">
                </div>

                <div class="auth-form-group">
                    <label for="full_name" class="auth-label">الاسم الكامل</label>
                    <input type="text" id="full_name" name="full_name" required class="auth-input"
                           placeholder="أدخل الاسم الكامل">
                </div>

                <div class="auth-form-group">
                    <label for="email" class="auth-label">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required class="auth-input"
                           placeholder="أدخل البريد الإلكتروني">
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-label">كلمة المرور</label>
                    <input type="password" id="password" name="password" required class="auth-input"
                           placeholder="أدخل كلمة المرور">
                </div>

                <div class="auth-form-group">
                    <label for="role" class="auth-label">الدور</label>
                    <select id="role" name="role" required class="auth-select">
                        <option value="">اختر الدور</option>
                        <option value="user">مستخدم</option>
                        <option value="admin">مدير</option>
                    </select>
                </div>

                <div class="auth-form-group">
                    <label for="branch_id" class="auth-label">الفرع</label>
                    <select id="branch_id" name="branch_id" required class="auth-select">
                        <option value="">اختر الفرع</option>
                        <!-- Branches will be loaded dynamically -->
                    </select>
                </div>

                <div class="flex justify-between items-center">
                    <a href="/NMaterailManegmentT/public/index.php?controller=user" class="auth-link">
                        العودة لقائمة المستخدمين
                    </a>
                    <button type="submit" class="auth-submit-btn">
                        إنشاء الحساب
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/NMaterailManegmentT/public/js/auth.js"></script>
</body>
</html> 