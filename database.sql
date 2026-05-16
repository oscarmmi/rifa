-- Crear base de datos (puedes ejecutar esto en phpMyAdmin)
-- CREATE DATABASE IF NOT EXISTS rifa_db;
-- USE rifa_db;

-- Tabla para la configuración general
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY DEFAULT 1,
    prize VARCHAR(255) DEFAULT 'Gran Premio',
    ticket_price DECIMAL(10, 2) DEFAULT 1000.00,
    raffle_date DATE DEFAULT NULL,
    lottery VARCHAR(100) DEFAULT '',
    CHECK (id = 1)
);

INSERT IGNORE INTO settings (id, prize, ticket_price, raffle_date, lottery) VALUES (1, 'Gran Premio', 1000.00, NULL, '');

-- Tabla para los 100 números (00-99)
CREATE TABLE IF NOT EXISTS tickets (
    ticket_number CHAR(2) PRIMARY KEY,
    status ENUM('available', 'reserved', 'paid') DEFAULT 'available',
    buyer_name VARCHAR(100) DEFAULT '',
    buyer_phone VARCHAR(20) DEFAULT ''
);

-- Insertar los 100 números iniciales (00 a 99)
-- Usaremos un procedimiento simple o inserts directos para asegurar que existan
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS InitializeTickets()
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE ticket_str CHAR(2);
    
    WHILE i < 100 DO
        SET ticket_str = LPAD(i, 2, '0');
        INSERT IGNORE INTO tickets (ticket_number) VALUES (ticket_str);
        SET i = i + 1;
    END WHILE;
END //
DELIMITER ;

CALL InitializeTickets();
DROP PROCEDURE IF EXISTS InitializeTickets;
