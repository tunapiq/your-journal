<?php $active_tab = $_GET['tab']??1; ?>
<!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 1? 'active' : ''); ?>" data-toggle="tab" href="#home1">Submit Papers</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 2? 'active' : ''); ?>" data-toggle="tab" href="#home2">Editors On My Papers</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 3? 'active' : ''); ?>" data-toggle="tab" href="#home3">Reviewers On My Papers</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 4? 'active' : ''); ?>" data-toggle="tab" href="#home4">My Papers</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo ($active_tab == 5? 'active' : ''); ?>" data-toggle="tab" href="#home5">Paper Withdrawals</a>
  </li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane container <?php echo ($active_tab == 1? 'active' : 'fade'); ?>" id="home1">
    <h5 class="mb-5 mt-5">Submit New Paper</h5>
    <form action="" method="post" class="">
      <input type="hidden" value="create_paper" name="action"/>
      <div class="form-group">
        <label for="title" >Title</label>
        <input name="title" type="text" id="title" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="author" >Author</label>
        <input name="author" value="<?php echo $user['data']['name']; ?>" type="text" id="author" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="article" >Article</label>
        <textarea name="article" class="form-control" id="article" rows="15"></textarea>
      </div>
      <button class="btn btn-primary btn-block" type="submit">Submit Paper</button>
    </form>
  </div>
  <div class="tab-pane container <?php echo ($active_tab == 2? 'active' : 'fade'); ?>" id="home2">
    <h5 class="mb-5 mt-5">My Paper Editors </h5>
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
            <form class="" method="get" action="">
             <input type="hidden" name="action" value="view_editor_comments">
             <input type="hidden" name="tab" value="2">
             <input type="hidden" name="editor_id" value="<?php echo $paper_editor['editor_id']; ?>"/>
             <input type="hidden" name="paper_id" value="<?php echo $paper_editor['paper_id']; ?>"/>
             <button class="btn btn-sm">View Editor Comments</button>
            </form>
          </div>
        </div>
      </div>
    <?php }?>
   </div>
  </div>
  <div class="tab-pane container <?php echo ($active_tab == 3? 'active' : 'fade'); ?>" id="home3"><br> to be implemented later...</div>
  <div class="tab-pane container <?php echo ($active_tab == 4? 'active' : 'fade'); ?>" id="home4">
    <h5 class="mb-5 mt-5">Submitted Papers</h5>
    <div class="row">

        <?php while($researcher_paper = $researcher_papers -> fetch_assoc()) {?>
          <div class="col-md-12 mb-5">
            <div class="card p-0">
              <ul class="list-group list-group-flush">
                <li class="list-group-item clearfix"><small class="text-muted">Paper ID (PID)</small> <br> <?php echo $researcher_paper['id']; ?></li>
                <li class="list-group-item clearfix"><small class="text-muted">Title</small> <br> <?php echo $researcher_paper['title']; ?></li>
                <li class="list-group-item clearfix"><small class="text-muted">Author</small> <br> <?php echo $researcher_paper['author']; ?></li>
                <li class="list-group-item clearfix"><small class="text-muted">Status</small> <br> <?php echo $researcher_paper['status']; ?></li>
                <li class="list-group-item clearfix"><small class="text-muted">Article</small> <br> <?php echo nl2br($researcher_paper['article']); ?></li>
              </ul>
            </div>
          </div>
        <?php }?>


    </div>
  </div>
  <div class="tab-pane container <?php echo ($active_tab == 5? 'active' : 'fade'); ?>" id="home5"><br> to be implemented later...</div>
</div>
