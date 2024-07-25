<?php
header('Content-Type: application/json');
include 'config.php';

// Mengambil data pesan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT * FROM messages ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    $messages = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'name' => htmlspecialchars($row['name']),
                'message' => htmlspecialchars($row['message']),
                'created_at' => $row['created_at']
            ];
        }
    }

    // Menghitung total pesan
    $countSql = "SELECT COUNT(*) as total FROM messages";
    $countResult = $conn->query($countSql);
    $total = $countResult->fetch_assoc()['total'];

    echo json_encode([
        'messages' => $messages,
        'total' => $total,
        'page' => $page,
        'limit' => $limit
    ]);
    exit;
}

// Mengirim data pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $conn->real_escape_string($data['name']);
    $message = $conn->real_escape_string($data['message']);

    $sql = "INSERT INTO messages (name, message) VALUES ('$name', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    exit;
}

// Menutup koneksi
$conn->close();
