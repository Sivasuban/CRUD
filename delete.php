<?php
include "config.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

  
    $sql = "DELETE FROM users WHERE id='$user_id'";

    
    if ($conn->query($sql) === TRUE) {
        
        header('Location: view.php?deleted=true');
        exit(); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
