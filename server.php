<?php
header('Content-Type: application/json');
include 'config.php';

// Mengambil data pesan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM messages ORDER BY created_at DESC";
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

    echo json_encode($messages);
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
