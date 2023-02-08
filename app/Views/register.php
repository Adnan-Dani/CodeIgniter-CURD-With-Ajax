<?php include 'includes/head.php';
include 'includes/navbar.php'; ?>


<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <h2 class="my-3">Register</h2>
        <form action="" class="col-md-8" id="registerform" validate>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Faizan" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="cpassword" id="cpassword" required>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Register</button>
            </div>
            <div class="my-3 text-center">
                <a class="nav-link" href="<?= base_url('/') ?>">Login?</a>
            </div>
        </form>
    </div>
</div>
<?= include 'includes/footer.php' ?>
<script>
    $('#registerform').validate({
        rules: {
            name: {
                required: true,
                maxlength: 50
            },
            email: {
                required: true,
                email: true,
                maxlength: 100
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 255, 
            },
            cpassword: {
                required: true,
                minlength: 8,
                maxlength: 255,
                equalTo: "#password"
            },

        },

        submitHandler: function(form, e) {
            e.preventDefault();
            $.post('register', $('form').serialize(), (data) => {
                console.log(data);
                console.log(typeof(data));
                var result = JSON.parse(data);
                if (result.status == true) {
                    swal.fire({
                        'icon': 'success',
                        'title': 'Success',
                        'text': result.message
                    }).then(() => {
                        window.location = '<?= base_url() ?>/users';
                    })
                } else {
                    swal.fire({
                        'icon': 'error',
                        'title': 'Failed',
                        'text': result.message
                    })

                }
            });

        }
    });
</script>