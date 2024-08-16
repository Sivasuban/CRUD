<?php
include "config.php";

// Initialize message variables
$successMessage = '';
$errorMessage = '';

if (isset($_POST['update'])) {
    $user_id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    // Hash the password before updating
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL update statement
    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email', password='$hashed_password', gender='$gender' WHERE id='$user_id'";

    // Execute the update query
    if ($conn->query($sql) === TRUE) {
        // Redirect to the same page with a success flag
        header('Location: update.php?id=' . $user_id . '&success=true');
        exit(); // Important to stop further execution
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare the SQL select statement
    $sql = "SELECT * FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $first_name = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $password = $row['password'];
        $gender = $row['gender'];
        $id = $row['id'];
    } else {
        header('Location: view.php');
        exit();
    }
}

// Check if the form was successfully submitted
if (isset($_GET['success'])) {
    $successMessage = 'Record updated successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="./create.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="main_div">
        <div class="child_div">
            <h2 class="font">User Update Form</h2>

            <?php if ($successMessage): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
                <script>
                    
                    setTimeout(function() {
                        alert('Record updated successfully. You will now be redirected.');
                        window.location.href = 'view.php';
                    }, 2000);
                </script>
            <?php elseif ($errorMessage): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="d-flex container-fluid w-50 mt-5">
                    <div class="justify-content-center align-items-center">
                        <div class="row">
                            <h3 class="text-center font">Personal Information:</h3>

                            <div class="mt-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($first_name); ?>">
                            </div>

                            <div class="mt-3">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>">
                            </div>

                            <div class="mt-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>">
                            </div>

                            <div class="mt-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($password); ?>">
                            </div>

                            <div class="mt-3">
                                <label for="gender" class="form-label">Gender</label><br>
                                <input type="radio" name="gender" value="male" <?php echo $gender === 'male' ? 'checked' : ''; ?>> Male
                                <input type="radio" name="gender" value="female" <?php echo $gender === 'female' ? 'checked' : ''; ?>> Female
                            </div>

                            <div class="text-center mt-5">
                                <input type="submit" name="update" value="Update" class="btn btn-primary">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
