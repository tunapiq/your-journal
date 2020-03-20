<?php $active_tab = $_GET['tab']??1; ?>
<!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 1? 'active' : ''); ?>" data-toggle="tab" href="#home1">Create Users</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 2? 'active' : ''); ?>" data-toggle="tab" href="#home2">Manage Users</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 3? 'active' : ''); ?>" data-toggle="tab" href="#home3">Assign/Unassign Editors</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 4? 'active' : ''); ?>" data-toggle="tab" href="#home4">Researcher Papers</a>
  </li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane container <?php echo ($active_tab == 1? 'active' : 'fade'); ?>" id="home1">
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
  </div>
  <div class="tab-pane container <?php echo ($active_tab == 2? 'active' : 'fade'); ?>" id="home2">
    <h5 class="mb-5 mt-5">Manage Users</h5>
      <div class="row">
        <?php foreach ($user_types as $type => $rows) {?>
          <div class="col-md-12">
            <h6 class="mb-4"> <?php echo $type;?>s</h6>
          </div>

          <?php while($row = $rows -> fetch_assoc()) {?>
            <div class="col-md-6 mb-4">
              <div class="card p-0">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item clearfix"><small class="text-muted">Role ID</small><br /> <?php echo $type.'-'.$row['role_id']; ?></li>
                  <li class="list-group-item clearfix"><small class="text-muted">Name</small><br /> <?php echo $row['name']; ?></li>
                  <li class="list-group-item clearfix"><small class="text-muted">Phone</small><br /> <?php echo $row['phone']; ?></li>
                  <li class="list-group-item clearfix"><small class="text-muted">Email</small><br /> <?php echo $row['email']; ?></li>
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
  <div class="tab-pane container <?php echo ($active_tab == 3? 'active' : 'fade'); ?>" id="home3">
    <h5 class="mb-5 mt-5">Assign Editor To Paper</h5>

    <form action="" method="post" class="">
      <input type="hidden" value="assign_editor" name="action"/>
      <div class="form-group">
        <label for="editor">Editor</label>
        <select name="editor_id" id="editor" class="custom-select" required>
        <option value=""></option>
        <?php while($editor = $editors -> fetch_assoc()) {?>
          <option value="<?php echo $editor['id'];?>"><?php echo ($editor['name'] .'(Role ID: editor-'.$editor['id'].')');?></option>
        <?php }?>
        </select>
      </div>

      <div class="form-group">
        <label for="paper">Paper</label>
        <select name="paper_id" id="paper" class="custom-select" required>
        <option value=""></option>
        <?php

        while($paper = $papers -> fetch_assoc()) {?>
          <option value="<?php echo $paper['id'];?>"><?php echo ($paper['title'] .'(PID: '.$paper['id'].')');?></option>
        <?php }?>
        </select>
      </div>
    <button class="btn btn-primary btn-block" type="submit">Assign To Paper</button>
    </form>

    <h5 class="mb-5 mt-5">Assigned Paper Editors </h5>

    <div class="row">
    <?php
    while($paper_editor = $paper_editors -> fetch_assoc()) {?>
      <div class="col-md-6 mb-5">
        <div class="card p-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item clearfix"><small class="text-muted">Editor Name</small> <br> <?php echo $paper_editor['editor_name']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Editor Role ID</small> <br> <?php echo 'editor-'.$paper_editor['editor_id']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Paper Title</small> <br> <?php echo $paper_editor['paper_title']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Paper ID (PID)</small> <br> <?php echo $paper_editor['paper_id']; ?></li>
          </ul>
          <div class="p-2 pr-3 text-right">
            <form class="delete-form" method="post" action="">
             <input type="hidden" name="action" value="unassign_editor">
             <input type="hidden" name="editor_id" value="<?php echo $paper_editor['editor_id']; ?>"/>
             <input type="hidden" name="paper_id" value="<?php echo $paper_editor['paper_id']; ?>"/>
             <button class="btn btn-sm">Remove editor </button>
            </form>
          </div>
        </div>
      </div>
    <?php }?>
   </div>

  </div>
  <div class="tab-pane container <?php echo ($active_tab == 4? 'active' : 'fade'); ?>" id="home4">
    <h5 class="mb-5 mt-5">Researcher Papers</h5>
    <div class="row">
    <?php
    //reset position to 0 since we already used this object previously.
    $papers -> data_seek(0);
    while($paper = $papers -> fetch_assoc()) {?>
      <div class="col-md-12 mb-5">
        <div class="card p-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item clearfix"><small class="text-muted">Paper ID (PID)</small> <br> <?php echo $paper['id']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Researcher Role ID</small> <br> <?php echo 'researcher-'.$paper['researcher_id']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Title</small> <br> <?php echo $paper['title']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Author</small> <br> <?php echo $paper['author']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Status</small> <br> <?php echo $paper['status']; ?></li>
            <li class="list-group-item clearfix"><small class="text-muted">Article</small> <br> <?php echo nl2br($paper['article']); ?></li>
          </ul>
        </div>
      </div>
    <?php }?>
   </div>
  </div>

</div>
