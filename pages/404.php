<?php
// Session başlat - en başta olmalı
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';

$helper = Helper::getInstance();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Bulunamadı - <?php echo $helper->getSiteName(); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        .error-code {
            font-size: 8rem;
            color: #e74c3c;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 2rem;
            color: #2c3e50;
            margin: 20px 0;
        }
        .error-message {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .search-box {
            margin: 30px 0;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #ecf0f1;
            border-radius: 25px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }
        .search-box input:focus {
            border-color: #3498db;
        }
        .search-box button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Sayfa Bulunamadı</h1>
        <p class="error-message">
            Aradığınız sayfa mevcut değil veya taşınmış olabilir. 
            Lütfen URL'yi kontrol edin veya ana sayfaya dönün.
        </p>
        
        <div class="search-box">
            <form action="<?php echo $helper->getBaseUrl(); ?>/arama" method="GET">
                <input type="text" name="q" placeholder="İlan ara..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        
        <a href="<?php echo $helper->getBaseUrl(); ?>" class="btn">
            <i class="fas fa-home"></i> Ana Sayfaya Dön
        </a>
    </div>
</body>
</html>
