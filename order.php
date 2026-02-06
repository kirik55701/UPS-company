<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UPS Company - форма заказа источников бесперебойного питания">
    <meta name="keywords" content="заказать ИБП, купить UPS, форма заказа ИБП">
    <meta name="author" content="UPS Company">
    <title>UPS Company - Форма заказа ИБП</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
        .order-section {
            padding: 100px 0 80px;
            min-height: 100vh;
        }
        
        .order-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .order-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .alert-success {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 20px;
            border-radius: 8px;
        }
        
        .alert-danger {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 20px;
            border-radius: 8px;
        }
        
        @media (max-width: 768px) {
            .order-section {
                padding: 80px 0 40px;
            }
            
            .order-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <?php
    // Подключение к базе данных
    $host = 'localhost';
    $dbname = 'ups_company'; // имя вашей базы данных
    $username = 'root'; // ваш пользователь MySQL
    $password = ''; // ваш пароль MySQL
    
    // Обработка формы
    $errors = [];
    $success = false;
    $order_id = null;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Получаем данные из формы
        $product = htmlspecialchars($_POST['product'] ?? '');
        $qty = intval($_POST['qty'] ?? 1);
        $name = htmlspecialchars($_POST['name'] ?? '');
        $address = htmlspecialchars($_POST['address'] ?? '');
        $phone = htmlspecialchars($_POST['phone'] ?? '');
        
        // Простая валидация
        if (empty($product) || $product == '0') {
            $errors[] = "Выберите модель ИБП";
        }
        
        if ($qty < 1 || $qty > 100) {
            $errors[] = "Количество должно быть от 1 до 100";
        }
        
        if (empty($name)) {
            $errors[] = "Введите ФИО заказчика";
        }
        
        if (empty($address)) {
            $errors[] = "Введите адрес доставки";
        }
        
        if (empty($phone)) {
            $errors[] = "Введите телефон";
        }
        
        // Если нет ошибок, сохраняем в базу
        if (empty($errors)) {
            try {
                // Подключаемся к базе данных
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Подготавливаем SQL запрос
                $sql = "INSERT INTO orders (product, qty, name, address, phone) 
                        VALUES (:product, :qty, :name, :address, :phone)";
                
                $stmt = $pdo->prepare($sql);
                
                // Выполняем запрос с параметрами
                $stmt->execute([
                    ':product' => $product,
                    ':qty' => $qty,
                    ':name' => $name,
                    ':address' => $address,
                    ':phone' => $phone
                ]);
                
                // Получаем ID нового заказа
                $order_id = $pdo->lastInsertId();
                $success = true;
                
            } catch (PDOException $e) {
                $errors[] = "Ошибка при сохранении заказа. Пожалуйста, попробуйте позже.";
                // Для отладки можно раскомментировать:
                // $errors[] = $e->getMessage();
            }
        }
    }
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="assets/images/logo.jpg" alt="UPS Company Logo" width="40" height="40" class="d-inline-block align-text-top me-2">
                UPS Company
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">О компании</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.html">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.html">Портфолио</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacts.html">Контакты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="order.php">Заказ ИБП</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="order-section" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <div class="container order-container">
            <div class="order-card">
                <h1 class="order-title">Форма заказа ИБП</h1>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Заказ успешно оформлен!</h4>
                        <p>Ваш заказ №<?php echo $order_id; ?> принят в обработку.</p>
                        <p><strong>Детали заказа:</strong></p>
                        <ul>
                            <li>Модель ИБП: <?php echo htmlspecialchars($product); ?></li>
                            <li>Количество: <?php echo $qty; ?> шт.</li>
                            <li>Заказчик: <?php echo htmlspecialchars($name); ?></li>
                            <li>Телефон: <?php echo htmlspecialchars($phone); ?></li>
                        </ul>
                        <p>Наш менеджер свяжется с вами в течение 30 минут для подтверждения заказа.</p>
                        <hr>
                        <a href="order.php" class="btn btn-light mt-2">Оформить новый заказ</a>
                    </div>
                <?php else: ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h5>Ошибки при заполнении формы:</h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <!-- 1) Выпадающий список с доступными ИБП -->
                        <div class="mb-4">
                            <label class="form-label" for="product">Выберите модель ИБП *</label>
                            <select class="form-select" id="product" name="product" required>
                                <option value="0">-- Выберите модель --</option>
                                <option value="UPS-500 Home">UPS-500 Home (для дома) - 500 ВА</option>
                                <option value="UPS-1000 Office">UPS-1000 Office (для офиса) - 1000 ВА</option>
                                <option value="UPS-1500 Pro">UPS-1500 Pro (профессиональный) - 1500 ВА</option>
                                <option value="UPS-3000 Server">UPS-3000 Server (для серверов) - 3000 ВА</option>
                                <option value="UPS-5000 Industrial">UPS-5000 Industrial (промышленный) - 5000 ВА</option>
                                <option value="UPS-Online 10kVA">UPS-Online 10kVA (онлайн) - 10000 ВА</option>
                            </select>
                            <div class="form-text">Выберите модель из списка доступных ИБП</div>
                        </div>
                        
                        <!-- 2) Кол-во штук -->
                        <div class="mb-4">
                            <label class="form-label" for="qty">Количество, шт. *</label>
                            <input type="number" class="form-control" id="qty" name="qty" 
                                   min="1" max="100" value="1" required>
                            <div class="form-text">Минимум 1, максимум 100 штук</div>
                        </div>
                        
                        <!-- 3) Поле ФИО Заказчика -->
                        <div class="mb-4">
                            <label class="form-label" for="name">ФИО Заказчика *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Иванов Иван Иванович" required>
                            <div class="form-text">Введите полное имя</div>
                        </div>
                        
                        <!-- 4) Адрес доставки -->
                        <div class="mb-4">
                            <label class="form-label" for="address">Адрес доставки *</label>
                            <textarea class="form-control" id="address" name="address" 
                                      rows="3" placeholder="Город, улица, дом, квартира" required></textarea>
                            <div class="form-text">Укажите полный адрес для доставки</div>
                        </div>
                        
                        <!-- 5) Телефон -->
                        <div class="mb-4">
                            <label class="form-label" for="phone">Телефон *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   placeholder="+7 (999) 123-45-67" required>
                            <div class="form-text">Номер для связи по заказу</div>
                        </div>
                        
                        
                        <button type="submit" class="btn btn-submit">Оформить заказ</button>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">* - поля, обязательные для заполнения</small>
                        </div>
                    </form>
                <?php endif; ?>
                
                <div class="mt-4 pt-4 border-top">
                    <h5 class="text-center mb-3">Дополнительная информация</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="text-primary">
                                <h6>🚚 Доставка</h6>
                                <small>По всей России 2-5 дней</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-primary">
                                <h6>⏰ Поддержка</h6>
                                <small>Пн-Пт 9:00-18:00</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-primary">
                                <h6>📞 Контакты</h6>
                                <small>+7 (495) 123-45-67</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>UPS Company</h5>
                    <p>Ведущий производитель источников бесперебойного питания в России. Качество, надежность и инновации.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="bi bi-telephone"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-envelope"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Продукция</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="index.html#products">Для дома</a></li>
                        <li><a href="index.html#products">Для офиса</a></li>
                        <li><a href="index.html#products">Промышленные</a></li>
                        <li><a href="index.html#products">Аккумуляторы</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Компания</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="about.html">О нас</a></li>
                        <li><a href="services.html">Услуги</a></li>
                        <li><a href="portfolio.html">Проекты</a></li>
                        <li><a href="contacts.html">Контакты</a></li>
                        <li><a href="order.php">Заказ ИБП</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Контакты</h5>
                    <ul class="list-unstyled footer-links">
                        <li>📞 +7 (495) 123-45-67</li>
                        <li>✉️ info@ups-company.ru</li>
                        <li>📍 Москва, ул. Промышленная, 15</li>
                        <li>🕒 Пн-Пт: 9:00-18:00</li>
                        <li>🚚 Доставка по России</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 UPS Company. Все права защищены.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-light me-3">Политика конфиденциальности</a>
                    <a href="sitemap.xml" class="text-light">Карта сайта</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Простая валидация на стороне клиента
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const phone = document.getElementById('phone').value;
                    const phonePattern = /^[\d\s\-\+\(\)]+$/;
                    
                    if (!phonePattern.test(phone)) {
                        e.preventDefault();
                        alert('Пожалуйста, введите корректный номер телефона');
                        return false;
                    }
                    
                    const qty = document.getElementById('qty').value;
                    if (qty < 1 || qty > 100) {
                        e.preventDefault();
                        alert('Количество должно быть от 1 до 100 штук');
                        return false;
                    }
                    
                    return true;
                });
            }
            
            // Автозаполнение телефона
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        if (!value.startsWith('7') && !value.startsWith('8')) {
                            value = '7' + value;
                        }
                        if (value.length > 11) {
                            value = value.substring(0, 11);
                        }
                        
                        let formatted = '+7';
                        if (value.length > 1) {
                            formatted += ' (' + value.substring(1, 4);
                        }
                        if (value.length > 4) {
                            formatted += ') ' + value.substring(4, 7);
                        }
                        if (value.length > 7) {
                            formatted += '-' + value.substring(7, 9);
                        }
                        if (value.length > 9) {
                            formatted += '-' + value.substring(9, 11);
                        }
                        
                        e.target.value = formatted;
                    }
                });
            }
        });
    </script>
</body>
</html>