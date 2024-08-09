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
                            <h2 class="text-center">Reset Password</h2>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" class="form-control border-secondary" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="cPassword" class="form-label">Confirm Password</label>
                            <input type="password" id="cPassword" class="form-control border-secondary" />
                        </div>

                        <div class="d-none">
                            <input type="text" id="vcode" value="<?php echo $_GET['code']; ?>" />
                        </div>

                        <div id="errorMsgDiv" class="d-none">
                            <div class="alert alert-danger" id="errorMsg"></div>
                        </div>

                        <div class="d-grid">
                            <button onclick="resetPassword();" class="btn btn-primary">Reset</button>
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