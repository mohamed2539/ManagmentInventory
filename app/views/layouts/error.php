<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ - نظام إدارة المواد</title>
    <link href="<?= $this->asset('css/bootstrap.rtl.min.css') ?>" rel="stylesheet">
    <link href="<?= $this->asset('css/style.css') ?>" rel="stylesheet">
    <style>
        .error-container {
            text-align: center;
            padding: 50px 15px;
            max-width: 600px;
            margin: 50px auto;
        }
        .error-code {
            font-size: 120px;
            color: #dc3545;
            font-weight: bold;
            margin: 20px 0;
        }
        .error-message {
            font-size: 24px;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .error-details {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
            direction: ltr;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <?= $content ?>
    <script src="<?= $this->asset('js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html> 