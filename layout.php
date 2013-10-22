<?php
function print_header() {
?>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">GeoDisplay</a>
          <div class="nav-collapse navbar-responsive-collapse collapse" style="height: 0px;">
            
            <ul class="nav pull-right">
			<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Edit <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="tags.php">Edit your tags</a></li>
                </ul>
              </li>
              <li class="divider-vertical"></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!-- /.nav-collapse -->
        </div>
      </div><!-- /navbar-inner -->
    </div>

<?php
}
function print_header_login() {
?>
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">GeoDisplay</a>
          <div class="nav-collapse navbar-responsive-collapse collapse" style="height: 0px;">
            <ul class="nav">
            </ul>
          </div><!-- /.nav-collapse -->
        </div>
      </div><!-- /navbar-inner -->
    </div>
<?php
}
?>