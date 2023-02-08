<?php include 'includes/head.php';
include 'includes/navbar.php'; ?>


<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <h2 class="my-3">Login</h2>
        <form action="#" class="col-md-8 " id="form" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary"  type="submit">Login</button> 
            </div>
            <div class="my-3 text-center">
                <a class="nav-link" href="<?=base_url('register')?>">Register Account?</a>
            </div>
        </form> 
    </div>
</div>
<?php include 'includes/footer.php' ?>
<script> 
$(document).ready(function() {
$("#basic-form").validate();
})
    $('#form').validate({
      rules: {
        email: {
          required: true,
          email: true
        },
        password: {
          required: true,
        },

      }, 
      messages: {

        email: {
          required: " Please enter a email",
          email: " Please enter valid email"
        },
        password: {
          required: " Please enter a password",
        },
      },
      submitHandler: function(form, e) {
        e.preventDefault(); 
        $.post('auth', $('form').serialize(), (data) => {
          console.log(data);
          var result = JSON.parse(data);
          if(result.status == true){
            swal.fire({
              'icon': 'success',
              'title': 'Authentication',
              'text': result.message
            }).then(()=>{
              window.location = '<?=base_url()?>/users';
            })
          }else {
            swal.fire({
              'icon': 'info',
              'title': 'Authentication',
              'text': result.message
            })

          }
        })

        return false;
      }
    });
  </script>