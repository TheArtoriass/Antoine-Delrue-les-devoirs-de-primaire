<?php
// update_children.php

include 'db.php';

// profile.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$parent_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'add' && !empty($_POST['add_child'])) {
        $child_id = $_POST['add_child'];
        $stmt = $pdo->prepare("INSERT INTO user_relationships (parent_id, child_id) VALUES (?, ?)");
        $stmt->execute([$parent_id, $child_id]);
        echo "Enfant ajouté avec succès.";
    } elseif ($_POST['action'] == 'remove' && !empty($_POST['remove_child'])) {
        $child_id = $_POST['remove_child'];
        $stmt = $pdo->prepare("DELETE FROM user_relationships WHERE parent_id = ? AND child_id = ?");
        $stmt->execute([$parent_id, $child_id]);
        echo "Enfant supprimé avec succès.";
    }
}

header('Location: profile.php');
exit;
?>