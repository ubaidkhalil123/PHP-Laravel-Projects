<?php
session_start();

include "db_connection.php";

// Redirect to login if 2FA session is not set
if (!isset($_SESSION['two_factor_code'])) {
    header("Location: login.php");
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['two_factor_code'];

    if ($entered_code == $_SESSION['two_factor_code']) {
        // Successful 2FA, log in the user
        $_SESSION['user_id'] = $_SESSION['two_factor_user_id'];
        $_SESSION['email'] = $_SESSION['email']; 
        
        // Clear the two-factor session data
        unset($_SESSION['two_factor_code']);
        unset($_SESSION['two_factor_user_id']);
        
        
        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid 2FA code
        $code_error = "Invalid 2FA code.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">2FA Verification</h2>
                <form action="" method="post" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="two_factor_code">Enter 2FA Code:</label>
                        <input type="text" class="form-control <?php echo isset($code_error) ? 'is-invalid' : ''; ?>" id="two_factor_code" name="two_factor_code" required>
                        <div class="invalid-feedback">
                            <?php echo isset($code_error) ? htmlspecialchars($code_error) : 'Please enter the 2FA code.'; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Verify</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
