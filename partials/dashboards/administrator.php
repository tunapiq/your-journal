<!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home1">Manage Users</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#home2">Manage Editors</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#home3">Papers</a>
  </li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane container active" id="home1">
    <h5 class="mb-5 mt-5">Create New User</h5>
    <form action="" method="post" class="">
      <input type="hidden" value="create_user" name="action"/>
      <div class="form-group">
        <label for="inputName" >Name</label>
        <input name="name" type="text" id="inputName" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="inputEmail" >Email address</label>
        <input name="email" type="email" id="inputEmail" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="inputPhone" >Phone</label>
        <input name="phone" type="text" id="inputPhone" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="inputPassword" >Password</label>
        <input name="password" type="password" id="inputPassword" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="inputRole" >Assign Role</label>
        <select name="user_type" id="inputRole" class="custom-select" required>
        <option value=""></option>
        <option value="administrator">Administrator</option>
        <option value="researcher">Researcher</option>
        <option value="reviewer">Reviewer</option>
        <option value="editor">Editor</option>
        </select>
    </div>

    <button class="btn btn-primary btn-block" type="submit">Add User</button>

    </form>
    <p><br><br><br><br></p>
    <h5 class="mb-5 mt-3">Edit Existing User</h5>
    <div class="row">
      <?php foreach ($user_types as $type => $rows) {?>
        <div class="col-md-12">
          <h6 class="mb-4"> <?php echo $type;?>s</h6>
        </div>

        <?php while($row = $rows -> fetch_assoc()) {?>
          <div class="col-md-6 mb-4">
            <div class="card p-0">
              <ul class="list-group list-group-flush">
                <li class="list-group-item clearfix"><?php echo $row['name']; ?></li>
                <li class="list-group-item clearfix"><?php echo $row['phone']; ?></li>
                <li class="list-group-item clearfix"><?php echo $row['email']; ?></li>
              </ul>
              <div class="p-2 pr-3 text-right">
                <form class="delete-form" method="post" action="">
                 <input type="hidden" name="action" value="delete_user">
                 <input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
                 <button class="btn btn-sm"><i class="fa fa-trash"></i> Delete </button>
                </form>
              </div>
            </div>
          </div>
        <?php }?>

        <div class="col-md-12">
        <br><br><br>
        </div>

      <?php }?>
    </div>
  </div>
  <div class="tab-pane container fade" id="home2">...</div>
  <div class="tab-pane container fade" id="home3">...</div>
</div>
