<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">

                        <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                        <?php elseif ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('login/forgot_password') ?>">
                            <div class="form-group">
                                <label for="email">Enter your registered email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                            <a href="<?= base_url('login') ?>" class="btn btn-link btn-block">Back to Login</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>