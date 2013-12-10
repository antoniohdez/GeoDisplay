<?php 

require_once("driver.php");
require_once("layout.php");
$driver = new dbDriver();

if(!isset($_SESSION["id"])){
	header('Location: login.php?err=2');
}

$msg = isset($_GET["msg"]) ? $_GET["msg"] : 0;

$id = $_SESSION["id"];
$name = '';
$email = '';
$points = '';
$country = '';
$city = '';
$uploadedlogo = '';

if(isset($_GET["submit"])){
	$target_path = "logo/";
    $target_path = $target_path.basename($_FILES['uploadedlogo']['name']); 
    if(! move_uploaded_file($_FILES['uploadedlogo']['tmp_name'], $target_path)) { 
        header("Location: index.pgp?msg=7");
    } 

	$name = $_POST["name"];
	$email = $_POST["email"];
	$country = $_POST["country"];
	$city = $_POST["city"];

    $success = $driver->editUser($id, $name, $email, $country, $city, $target_path);

	if ($success){
		header("Location: index.php?msg=6");
	} 
    else {
        header("Location: index.php?msg=8");
    }
	
}

$user = $driver->getUser($id);
$name = $user["name"];
$email = $user["email"];
$points = $user["points"];
$country = $user["country"];
$city = $user["city"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    
    <title>Edit your profile : GeoDisplay</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/register_form.css" rel="stylesheet">
    <link href="css/general_style.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="js/map.js"></script>
	<script src="js/jquery.js"></script>
    <script src="js/jquery.validate.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#edit_tag_form").validate({
                highlight: function(element, errorClass) {
                    $(element).addClass("input-error");
                },
                rules :{
                    name : { required : true },
                    email : { required : true, email : true },
					country : { required : true },
					city :{ required : true }
                },
                messages :{
                    name : { required : "" },
                    email : { required : "" },
					country :{ required : "" },
					city :{ required : "" }
                },
                errorElement: "div",
                wrapper: "div", 
                errorPlacement: function(error, element) {
                    offset = element.offset();
                    error.insertBefore(element)
                    error.css('position', 'absolute');
                    error.css('left', offset.left + element.outerWidth() + 10);
                    error.css('top', offset.top + 5);
                }
            });
    });
	</script>
    
</head>
<body> 
    <?php print_header(); ?>  
	<div class="container">
		<div class="row">
			<div class="span10 offset1">
                <form form action="user.php?submit" enctype="multipart/form-data" class="form-horizontal" id="edit_tag_form" method="POST">
                    <legend>Edit your profile</legend>
                    <div class="row-fluid">
                        <div class="span5">

                            <label for="id">Id</label>
                            <input type="text" id="id" name="id" value="<?php echo $id ?>" class="input-block-level" readonly>

                            <label for="points"><br>Points</label>
                            <input type="text" id="points" name="points" value="<?php echo $points ?>" class="input-block-level" readonly>

                            <label for="name"><br>Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo $name ?>" class="input-block-level">
                        	
							<label for="email"><br>Email *</label>
							<input type="text" id="email" name="email" value="<?php echo $email ?>" class="input-block-level">
							
							<label for="country"><br>Country *</label>
							<input type="text" id="country" name="country" value="<?php echo $country ?>" class="input-block-level">

							<label for="city"><br>City *</label>
							<input type="text" id="city" name="city" value="<?php echo $city ?>" class="input-block-level">
                                                	
                            <label for="uploadedlogo"><br>Logo *</label>
							<input id="uploadedlogo" name="uploadedlogo" type="file">
                        </div>
                    </div>
                	<div class="row-fluid">
                    	<div class="span12" style="margin-top:15px;">
                    		<button type="submit" class="btn btn-primary">Save</button>
                        	<input type="reset" class="btn" value="Cancel" >
							
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>                
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.file-input.js"></script>
</body>
</html>