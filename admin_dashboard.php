<?php
// admin_dashboard.php
session_start();

// Проверка авторизации
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Настройки подключения к БД
$host = 'localhost';
$dbname = 'ups_company';
$username = 'root';
$password = '';

// Получаем заказы
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPS Company - Админ панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <span class="me-2">UPS Company</span>
                <small class="text-muted">Админ панель</small>
            </a>
            <div class="navbar-text">
                Вы вошли как: <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                <a href="logout.php" class="btn btn-sm btn-outline-light ms-3">Выйти</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Заказы ИБП</h1>
        <p>Всего заказов: <strong><?php echo count($orders); ?></strong></p>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Дата</th>
                        <th>Товар</th>
                        <th>Кол-во</th>
                        <th>Заказчик</th>
                        <th>Телефон</th>
                        <th>Адрес</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Заказов пока нет</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($order['product']); ?></td>
                            <td><?php echo $order['qty']; ?> шт.</td>
                            <td><?php echo htmlspecialchars($order['name']); ?></td>
                            <td><?php echo htmlspecialchars($order['phone']); ?></td>
                            <td><?php echo htmlspecialchars($order['address']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>