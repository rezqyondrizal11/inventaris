<?php $this->load->view('layout/header'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Edit Profile</h1>

    <!-- Menampilkan pesan error jika ada -->
    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User Profile</h6>
        </div>
        <div class="card-body">
            <form method="post" action="<?= site_url('profile/edit'); ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password (optional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>