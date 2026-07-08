<div class="ltn__login-area pb-65 pt-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Reset Password</h1>
                </div>
            </div>
        </div>

        <div class="row clearfix">

            <div class="form-column column col-lg-12 col-md-12 col-sm-12">

                <div class="sec-title">
                    <h2>Reset Password</h2>
                    <div class="separate"></div>
                </div>

                <div class="styled-form login-form">

                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('message'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('auth/updatePassword') ?>" method="post">

                        <input type="hidden" name="token" value="<?= $token ?>">

                        <!-- New Password -->
                        <div class="form-group position-relative">
                            <input type="password"
                                   name="new_password"
                                   id="new_password"
                                   placeholder="New Password"
                                   class="form-control"
                                   required>

                            <span class="toggle-password" data-target="new_password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group position-relative">
                            <input type="password"
                                   name="confirm_password"
                                   id="confirm_password"
                                   placeholder="Confirm Password"
                                   class="form-control"
                                   required>

                            <span class="toggle-password" data-target="confirm_password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>

                        <div class="clearfix">
                            <div class="form-group pull-left">
                                <button type="submit" class="theme-btn btn-style-three">
                                    <span class="txt">Update Password</span>
                                </button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.position-relative {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #666;
    z-index: 10;
}

.toggle-password:hover {
    color: #000;
}

.form-control {
    padding-right: 45px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.toggle-password').forEach(function(btn) {

        btn.addEventListener('click', function() {

            let input = document.getElementById(this.dataset.target);
            let icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }

        });

    });

});
</script>