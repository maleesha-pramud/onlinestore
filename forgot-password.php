<!DOCTYPE html>
<html lang="en">

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
                            <h2 class="text-center">Forgot Password</h2>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">User Name/Email Address</label>
                            <input type="text" id="email" class="form-control border-secondary" />
                        </div>

                        <div class="d-grid">
                            <button onclick="forgotPassword();" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign In Box -->
        </div>
    </div>

    <?php include 'components/script.php'; ?>
</body>

</html>