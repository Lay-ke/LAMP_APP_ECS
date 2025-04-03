<?php
function saveRecord($pdo, $data) {
    $sql = "INSERT INTO records (title, description, date_created) VALUES (:title, :description, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':title' => $data['title'],
        ':description' => $data['description']
    ]);
}

function getRecords($pdo) {
    $stmt = $pdo->query("SELECT * FROM records ORDER BY date_created DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRecord($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM records WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateRecord($pdo, $data) {
    $sql = "UPDATE records SET title = :title, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $data['id'],
        ':title' => $data['title'],
        ':description' => $data['description']
    ]);
}

function deleteRecord($pdo, $id) {
    $sql = "DELETE FROM records WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}