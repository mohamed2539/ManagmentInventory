<?php $this->setLayout('error'); ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة غير موجودة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #ffc107;
        }
        .error-message {
            font-size: 1.5rem;
            margin: 1rem 0;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>الصفحة غير موجودة</h1>
        <div class="error-code">404</div>
        <p class="error-message">عذراً، الصفحة التي تبحث عنها غير موجودة</p>
        <a href="<?= $this->url('') ?>" class="btn btn-primary">العودة للصفحة الرئيسية</a>
    </div>
</body>
</html> 