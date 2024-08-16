<?php
include "config.php";

// Initialize variables
$first_name = '';
$last_name = '';
$email = '';
$password = '';
$gender = '';
$errors = [];
$successMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['firstname'] ?? '');
    $last_name = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $gender = $_POST['gender'] ?? '';

    // Validate form fields
    if (empty($first_name)) $errors['firstname'] = 'First Name is required.';
    if (empty($last_name)) $errors['lastname'] = 'Last Name is required.';
    if (empty($email)) $errors['email'] = 'Email is required.';
    if (empty($password)) $errors['password'] = 'Password is required.';
    if (empty($gender)) $errors['gender'] = 'Gender is required.';

    // Proceed if no errors
    if (empty($errors)) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $gender);

        if ($stmt->execute()) {
            // Redirect to the same page with a success flag to avoid form resubmission
            header('Location: create.php?success=true');
            exit(); // Important to stop further execution
        } else {
            $errors['db'] = 'Database Error: ' . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}

// Check if the form was successfully submitted
if (isset($_GET['success'])) {
    $successMessage = 'New record created successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>
    <link rel="stylesheet" href="./create.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="main_div ">
        <div class="child_div ">

            <h2 class="font">Signup Form</h2>

            <?php if ($successMessage): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <h3 class="text-center font">Personal Information</h3>
                <div class="d-flex container-fluid  w-50 mt-5">

                    <div class="justify-content-center align-items-center">

                        <div class="row">


                            <div class="mb-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlspecialchars($first_name); ?>">
                                <div class="text-danger"><?php echo $errors['firstname'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo htmlspecialchars($last_name); ?>">
                                <div class="text-danger"><?php echo $errors['lastname'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                                <div class="text-danger"><?php echo $errors['email'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control">
                                <div class="text-danger"><?php echo $errors['password'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label><br>
                                <input type="radio" id="male" name="gender" value="male" <?php echo $gender === 'male' ? 'checked' : ''; ?>>
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="gender" value="female" <?php echo $gender === 'female' ? 'checked' : ''; ?>>
                                <label for="female">Female</label>
                                <div class="text-danger"><?php echo $errors['gender'] ?? ''; ?></div>
                            </div>

                            <div class="text-center mt-5">
                                <input type="submit" value="Submit" class="btn btn-primary">
                            </div>
                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>
</body>

</html>