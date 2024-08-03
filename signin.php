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
<html lang="en" data-bs-theme="white">

<head>
    <?php include './components/head.php'; ?>
</head>

<body>
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center align-items-center">

            <!-- Sign In Box -->
            <div id="signinBox" class="col-10 col-lg-4">
                <div class="row card shadow rounded-18">
                    <div class="card-body">
                        <div class="col-12 mb-3">
                            <h2 class="text-center">Sign In</h2>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">User Name/Email Address</label>
                            <input type="text" id="email2" class="form-control border-secondary" value="<?php echo ($email) ?>" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password2" class="form-control border-secondary" value="<?php echo ($password) ?>" />
                        </div>

                        <div class="form-check col-12 mb-3">
                            <input type="checkbox" id="remember-me" class="form-check-input" <?php if (isset($_COOKIE["email"])) { echo "checked"; } ?>>
                            <label for="remember-me" class="form-check-label border-secondary">Remember Me</label>
                        </div>

                        <div id="errorMsgDiv2" class="d-none">
                            <div class="alert alert-danger" id="errorMsg2"></div>
                        </div>

                        <div class="text-center">
                            <a href="forgot-password.php" class="link-secondary text-decoration-none">Forgot Password?</a>
                        </div>

                        <div class="col-12 text-center">
                            <p>Don't have an account? <a onclick="changeView()">Sign Up</a></p>
                        </div>

                        <div class="d-grid">
                            <button onclick="signIn()" class="btn btn-primary">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign In Box -->

            <!-- Sign Up Box -->
            <div id="signupBox" class="d-none col-10 col-lg-4">
                <div class="row card shadow rounded-18">
                    <div class="card-body">
                        <div class="col-12 mb-3">
                            <h2 class="text-center">Sign Up</h2>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" id="fname" class="form-control border-secondary" />
                            </div>

                            <div class="col-6 mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" id="lname" class="form-control border-secondary" />
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" id="mobile" class="form-control border-secondary" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" class="form-control border-secondary" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control border-secondary" />
                        </div>

                        <div class="col-12 text-center">
                            <p>Already have an account? <a onclick="changeView()">Sign In</a></p>
                        </div>

                        <div id="errorMsgDiv" class="d-none">
                            <div class="alert alert-danger" id="errorMsg"></div>
                        </div>

                        <div class="d-grid">
                            <button onclick="signUp()" class="btn btn-primary">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign Up Box -->

        </div>
    </div>

    <?php include './components/script.php'; ?>
</body>

</html>