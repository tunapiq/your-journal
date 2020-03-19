
<?php if(!isset($hide_footer_nav)){?>
<nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
  <p class="p-4 m-0 mx-auto text-muted">&copy; 2020, All Rights Reserved. Group 16.</p>
</nav>
<?php }?>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
$(".delete-form").on('submit', function(event){
	 if(!confirm("Are you sure?")) event.preventDefault();
});
</script>
</body>
</html>
