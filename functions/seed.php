<?php

require_once './vendor/autoload.php'; // Path to Composer autoload file

try {

    // Load environment variables from .env file if it exists
    $directory = dirname(__DIR__);
    if (file_exists($directory . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable($directory);
        $dotenv->load();
    } else {
        error_log('.env file not found, falling back to system environment variables.');
    }

    // Retrieve database credentials from environment variables
    $db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
    $db_user = $_ENV['DB_USER'] ?? getenv('DB_USER');
    $db_password = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
    $db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME');

    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Drop tables if they exist
    $tables = ['order_items', 'orders', 'users', 'books', 'publisher'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
    }

    // Create tables
    $createPublisherTableQuery = "
    CREATE TABLE publisher (
        publisherId INT AUTO_INCREMENT PRIMARY KEY,
        publisher_name VARCHAR(255) NOT NULL
    )";
    $pdo->exec($createPublisherTableQuery);

    $createBooksTableQuery = "
    CREATE TABLE books (
        book_isbn VARCHAR(20) PRIMARY KEY,
        book_title VARCHAR(255) NOT NULL,
        book_author VARCHAR(255),
        book_image VARCHAR(255),
        book_descr TEXT,
        book_price DECIMAL(10, 2),
        publisherId INT,
        created_at DATETIME,
        FOREIGN KEY (publisherId) REFERENCES publisher(publisherId)
    )";
    $pdo->exec($createBooksTableQuery);

    $createUsersTableQuery = "
    CREATE TABLE users (
        userId INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE,
        phone VARCHAR(20),
        passwordHash VARCHAR(255) NOT NULL,
        createdAt DATETIME,
        user_type VARCHAR(50)
    )";
    $pdo->exec($createUsersTableQuery);

    $createOrdersTableQuery = "
    CREATE TABLE orders (
        orderId INT AUTO_INCREMENT PRIMARY KEY,
        userId INT,
        amount DECIMAL(10, 2),
        order_date DATETIME,
        ship_name VARCHAR(255),
        ship_phone VARCHAR(20),
        ship_address TEXT,
        ship_city VARCHAR(255),
        ship_zip_code VARCHAR(20),
        ship_country VARCHAR(100),
        ship_email VARCHAR(255),
        order_ref VARCHAR(255) UNIQUE,
        FOREIGN KEY (userId) REFERENCES users(userId)
    )";
    $pdo->exec($createOrdersTableQuery);

    $createOrderItemsTableQuery = "
    CREATE TABLE order_items (
        oItemId INT AUTO_INCREMENT PRIMARY KEY,
        orderId INT,
        book_isbn VARCHAR(20),
        item_price DECIMAL(10, 2),
        quantity INT,
        FOREIGN KEY (orderId) REFERENCES orders(orderId),
        FOREIGN KEY (book_isbn) REFERENCES books(book_isbn)
    )";
    $pdo->exec($createOrderItemsTableQuery);

    // Insert data into publisher table
    $publisherQuery = "INSERT INTO publisher (publisher_name) VALUES 
    ('Publisher 1'),
    ('Publisher 2'),
    ('Publisher 3'),
    ('Publisher 4'),
    ('Publisher 5'),
    ('Publisher 6')";
    $pdo->exec($publisherQuery);
    
    // Insert data into books table
    $booksQuery = "INSERT INTO books (book_isbn, book_title, book_author, book_image, book_descr, book_price, publisherId, created_at) VALUES
    ('64568', 'Sample 102', 'Sample Author 2', 'dark-bg.jpg', 'Test - updated', 1200.00, 6, '2022-06-21 16:44:25'),
    ('978-0-321-94786-4', 'Learning Mobile App Development', 'Jakob Iversen, Michael Eierman', 'mobile_app.jpg', 'Now, one book can help you master mobile app development...', 20.00, 6, '2022-06-21 16:44:25')";
    $pdo->exec($booksQuery);

    // Insert data into users table
    $usersQuery = "INSERT INTO users (name, email, phone, passwordHash, createdAt, user_type) 
                   VALUES (:name, :email, :phone, :passwordHash, :createdAt, :user_type)";
    $stmt = $pdo->prepare($usersQuery);
    $usersData = [
        [
            ':name' => 'Haruna Rabiu',
            ':email' => 'haruna@1utar.my',
            ':phone' => '0116153804',
            ':passwordHash' => password_hash('#Password1', PASSWORD_DEFAULT),
            ':createdAt' => '2024-12-12 15:55:23',
            ':user_type' => 'ADMIN'
        ],
        [
            ':name' => 'Test User',
            ':email' => 'user@test.com',
            ':phone' => '111101111',
            ':passwordHash' => password_hash('test123', PASSWORD_DEFAULT),
            ':createdAt' => '2024-12-12 19:43:00',
            ':user_type' => ''
        ]
    ];
    foreach ($usersData as $user) {
        $stmt->execute($user);
    }

    echo "Tables dropped, recreated, and seeded successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
