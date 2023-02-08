<?php include 'includes/head.php';
include 'includes/navbar.php'; ?>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" id="editfrom" validate>
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" value="" id="id">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Faizan" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="row d-flex justify-content-center">
  <div class="col-md-6">
    <h2 class="my-3">Users</h2>
    <table class="table table-striped table-hover" class="col-md-6">
      <thead>
        <tr>
          <th scope="col">#Id</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) { ?>
          <tr>
            <th scope="row"><?= $user['user_id'] ?></th>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td>
              <button class="btn btn-success" onclick="editUser(<?= $user['user_id'] ?>)" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-pen-to-square"></i></button>
              <button class="btn btn-danger" onclick="deleteUser(<?= $user['user_id'] ?>)"><i class="fa-regular fa-trash-can"></i></button>
            </td>
          </tr>
        <?php } ?>

      </tbody>
    </table>
  </div>
</div>
<?= include 'includes/footer.php' ?>
<script>
  function deleteUser(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You want to delete!",
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.post("delete", {
            id
          }, (result) => {
            var obj = JSON.parse(result);

            if (obj.status == true) {
              Swal.fire(
                'Deleted!',
                obj.message,
                'success'
              ).then(() => {
                window.location.reload();
              })
            } else {
              Swal.fire(
                'Failed!',
                obj.message,
                'error'
              )
            }
          })
          .fail(function(result) {
            Swal.fire(
              'Failed!',
              'Failed to update.',
              'error'
            )
          })



      }
    })
  }

  function editUser(id) {
    $.post("getUser", {
      id
    }, (result) => {
      var obj = JSON.parse(result);
      console.log(obj);
      $('#id').val(id);
      $('#name').val(obj.name);
      $('#email').val(obj.email);
    });
  }
  $('#editfrom').validate({
    rules: {
      name: {
        required: true,
        maxlength: 50
      },
      email: {
        required: true,
        email: true,
        maxlength: 100
      }

    },

    submitHandler: function(form, e) {
      e.preventDefault();
      let data = {
        id: $('#id').val(),
        email : $('#email').val(),
        name : $('#name').val(),
      };
      console.log(data);
      $.post('updateUser', data, (data) => {
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