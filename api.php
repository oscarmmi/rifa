<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get_all') {
        // Obtener configuración
        $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
        $settings = $stmt->fetch();

        // Obtener tickets
        $stmt = $pdo->query("SELECT * FROM tickets ORDER BY ticket_number ASC");
        $tickets = $stmt->fetchAll();

        echo json_encode([
            'settings' => $settings,
            'tickets' => $tickets
        ]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    if ($action === 'update_settings') {
        $stmt = $pdo->prepare("UPDATE settings SET prize = ?, ticket_price = ?, raffle_date = ?, lottery = ? WHERE id = 1");
        $stmt->execute([$data['prize'], $data['ticket_price'], $data['raffle_date'], $data['lottery']]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'update_ticket') {
        $stmt = $pdo->prepare("UPDATE tickets SET status = ?, buyer_name = ?, buyer_phone = ? WHERE ticket_number = ?");
        $stmt->execute([
            $data['status'],
            $data['buyer_name'],
            $data['buyer_phone'],
            $data['ticket_number']
        ]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'clear_ticket') {
        $stmt = $pdo->prepare("UPDATE tickets SET status = 'available', buyer_name = '', buyer_phone = '' WHERE ticket_number = ?");
        $stmt->execute([$data['ticket_number']]);
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['error' => 'Acción no válida']);
?>
