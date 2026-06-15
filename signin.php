<?php
$email = "";
$password = "";
if (isset($_COOKIE["email"])) {
    $email = $_COOKIE["email"];
}
if (isset($_COOKIE["password"])) {
    $password = $_COOKIE["password"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './components/head.php'; ?>
    <link rel="stylesheet" href="./assets/css/auth.css" />
    <link rel="stylesheet" href="./assets/css/dashboard.css" />
</head>
<body class="auth-body">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/index.php" class="brand">
                <img src="/assets/images/logo/logo-only-text.png" alt="2nd Home" style="height: 40px;">
            </a>
            <p>Welcome back! Please sign in to continue.</p>
        </div>

        <div id="errorMsgDiv2" class="d-none">
            <div class="alert alert-danger" id="errorMsg2"></div>
        </div>

        <form onsubmit="signIn(); return false;">
            <div class="form-group">
                <label for="email2" class="form-label">Email Address</label>
                <input type="email" id="email2" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="password2" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password2" class="form-control" value="<?php echo htmlspecialchars($password); ?>" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center form-group">
                <div class="form-check">
                    <input type="checkbox" id="remember-me" class="form-check-input" <?php if (isset($_COOKIE["email"])) { echo "checked"; } ?>>
                    <label for="remember-me" class="form-check-label">Remember Me</label>
                </div>
                <a href="forgot-password.php" class="fs-sm">Forgot Password?</a>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
            <div class="text-center mt-3">
                <p class="text-secondary">Don't have an account? <a href="/signup.php">Sign Up</a></p>
            </div>
        </form>
    </div>
    <?php include './components/script.php'; ?>
</body>
</html>