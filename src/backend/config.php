<?php

// инициализация конфига бд
define('DB_HOST', 'mysql');
define('DB_PORT', '3306'); 
define('DB_NAME', 'courier_schedule'); 
define('DB_USER', 'root'); 
define('DB_PASSWORD', 'root'); 

// конект к бд
$conn = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

// todo
// желательно сделать, чтобы с тома mysql подтягивалось все это добро
$tables = [
    'couriers',
    'regions',
    'trips'
];

foreach ($tables as $table) {
    $sql = "SHOW TABLES LIKE '$table'";
    $result = $conn->query($sql);

    if ($result->rowCount() === 0) {
        switch ($table) {
            case 'couriers':
                $sql = "CREATE TABLE couriers (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    full_name VARCHAR(255) NOT NULL,
                    phone VARCHAR(20) NOT NULL,
                    email VARCHAR(255),
                    courier_status ENUM('active', 'inactive') DEFAULT 'active',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    );
                    INSERT INTO couriers (full_name, phone, email, courier_status) VALUES
                    ('John Smith', '+79111111111', 'john.smith@example.com', 'active'),
                    ('Peter Jones', '+79222222222', 'peter.jones@example.com', 'active'),
                    ('Mary Brown', '+79333333333', 'mary.brown@example.com', 'active'),
                    ('Alice Johnson', '+79444444444', 'alice.johnson@example.com', 'active'),
                    ('Bob Williams', '+79555555555', 'bob.williams@example.com', 'active'),
                    ('Carol Davis', '+79666666666', 'carol.davis@example.com', 'active'),
                    ('David Wilson', '+79777777777', 'david.wilson@example.com', 'active'),
                    ('Emily Garcia', '+79888888888', 'emily.garcia@example.com', 'active'),
                    ('Frank Rodriguez', '+79999999999', 'frank.rodriguez@example.com', 'active'),
                    ('Grace Miller', '+79000000000', 'grace.miller@example.com', 'active');";
                break;
            case 'regions':
                $sql = "CREATE TABLE regions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    delivery_time INT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    );
                    INSERT INTO regions (name, delivery_time) VALUES
                    ('Saint Petersburg', 2),
                    ('Ufa', 3),
                    ('Nizhny Novgorod', 2),
                    ('Vladimir', 3),
                    ('Kostroma', 2),
                    ('Yekaterinburg', 3),
                    ('Kovrov', 3),
                    ('Voronezh', 4),
                    ('Samara', 3),
                    ('Astrakhan', 3);
                    ";
                break;
            case 'trips':
                $sql = "CREATE TABLE trips (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    courier_id INT NOT NULL,
                    region_id INT NOT NULL,
                    trip_date DATE NOT NULL, -- начало доставки
                    trip_date_end DATE NOT NULL, -- дата, когда конец доставки
                    trip_status ENUM('planned', 'in_progress', 'completed') DEFAULT 'planned',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (courier_id) REFERENCES couriers(id),
                    FOREIGN KEY (region_id) REFERENCES regions(id)
                    );
                    
                    INSERT INTO trips (courier_id, region_id, trip_date, trip_date_end, trip_status) VALUES
                    (1, 1, '2024-05-26', '2024-05-28', 'completed'),
                    (9, 3, '2024-05-26', '2024-05-29', 'completed'),
                    (10, 2, '2024-05-26', '2024-05-30', 'completed'),
                    (2, 2, '2024-05-27', '2024-05-28', 'completed'),
                    (3, 3, '2024-05-28', '2024-05-31', 'completed'),
                    (4, 4, '2024-05-29', '2024-05-31', 'completed'),
                    (7, 7, '2024-06-06', '2024-06-08', 'completed'),
                    (8, 8, '2024-06-11', '2024-06-14', 'completed'),
                    (2, 1, '2024-06-15', '2024-06-20', 'completed'),
                    (3, 2, '2024-06-27', '2024-06-29', 'completed'),
                    (4, 3, '2024-07-07', '2024-07-10', 'completed'),
                    (5, 4, '2024-07-08', '2024-07-11', 'completed'),
                    (6, 5, '2024-07-09', '2024-07-12', 'completed'),
                    (7, 6, '2024-07-10', '2024-07-13', 'completed');";
                break;
        }
        
        if ($conn->exec($sql) === false) {
        } else {
        }
    } else {
        
    }
}