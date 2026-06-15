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
            <p>Create an account to start booking or hosting.</p>
        </div>

        <div id="errorMsgDiv" class="d-none">
            <div class="alert alert-danger" id="errorMsg"></div>
        </div>

        <form onsubmit="signUp(); return false;">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" id="fname" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" id="lname" class="form-control" required>
                </div>
            </div>
             <div class="form-group mt-3">
                <label for="mobile" class="form-label">Mobile</label>
                <input type="text" id="mobile" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" class="form-control" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
             <div class="text-center mt-3">
                <p class="text-secondary">Already have an account? <a href="/signin.php">Sign In</a></p>
            </div>
        </form>
    </div>
    <?php include './components/script.php'; ?>
</body>
</html>
