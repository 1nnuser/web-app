--
-- На всякий случай, а так не нужно ничего создавать. FakeData и создание таблиц вшито в config.php
--

-- Таблица с курьерами
CREATE TABLE couriers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    courier_status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Таблица с регионами
CREATE TABLE regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    delivery_time INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Таблица расписания поездок в регионы
CREATE TABLE trips (
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

-- Заполняем таблицу couriers
INSERT INTO couriers (full_name, phone, email, courier_status) VALUES
    ('John Smith', '+79111111111', 'john.smith@example.com', 'active'),
    ('Peter Jones', '+79222222222', 'peter.jones@example.com', 'active'),
    ('Mary Brown', '+79333333333', 'mary.brown@example.com', 'active');

-- Заполняем таблицу regions
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

INSERT INTO trips (courier_id, region_id, trip_date, trip_date_end, trip_status) VALUES
    (1, 1, '2023-05-26', '2023-05-28', 'completed'),
    (2, 2, '2023-05-27', '2023-05-28', 'completed'),
    (3, 3, '2023-05-28', '2023-05-31', 'completed'),
    (4, 4, '2023-05-29', '2023-05-31', 'completed'),
    (7, 7, '2023-06-06', '2023-06-08', 'completed'),
    (8, 8, '2023-06-11', '2023-06-14', 'completed'),
    (2, 1, '2023-06-15', '2023-06-20', 'completed'),
    (3, 2, '2023-06-27', '2023-06-29', 'completed'),
    (4, 3, '2023-07-07', '2023-07-10', 'completed'),
    (5, 4, '2023-07-08', '2023-07-11', 'completed'),
    (6, 5, '2023-07-09', '2023-07-12', 'completed'),
    (7, 6, '2023-07-10', '2023-07-13', 'completed');
