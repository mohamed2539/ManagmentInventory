<?php session_start();
    echo "<pre>";
    echo var_dump($_SESSION);
    echo "</pre>";
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة المواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/style.css" rel="stylesheet">
    <link href="/NMaterailManegmentT/public/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-header">نظام إدارة المواد</h1>
            <p class="auth-subheader">قم بتسجيل الدخول للمتابعة</p>

            <!-- Message Display -->
            <div id="message" class="hidden"></div>

            <!-- Login Form -->
            <form id="loginForm" class="auth-form">
                <div class="auth-form-group">
                    <label for="username" class="auth-label">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required class="auth-input" 
                           placeholder="أدخل اسم المستخدم">
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-label">كلمة المرور</label>
                    <input type="password" id="password" name="password" required class="auth-input"
                           placeholder="أدخل كلمة المرور">
                </div>

                <div class="auth-checkbox-group">
                    <input type="checkbox" id="remember" name="remember" class="auth-checkbox">
                    <label for="remember" class="text-sm text-gray-600">تذكرني</label>
                </div>

                <button type="submit" class="auth-submit-btn">
                    تسجيل الدخول
                </button>
            </form>
        </div>
    </div>

    <script src="/NMaterailManegmentT/public/js/auth.js"></script>
</body>
</html> 