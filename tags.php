<?php 

require_once("driver.php");
require_once("layout.php");
$driver = new dbDriver();

if(!isset($_SESSION["id"])){
	header('Location: login.php?err=2');
}
$editing = isset($_GET["edit"]);
$deleting = isset($_GET["delete"]);
$reading = !($editing || $deleting);
$msg = isset($_GET["msg"]) ? $_GET["msg"] : 0;

$tag_name = '';
$uploadedfile = '';
$description = '';
$url = '';
$url_title = '';
$latitude = '';
$longitude = '';
$id = '';
$success = true;

if($editing){
	if(isset($_GET["submit"])){
		$target_path = "uploads/";
		$target_path = $target_path.basename($_FILES['uploadedfile']['name']); 
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) { 
			//echo "File ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
		} else{
			//echo "Something is wrong, try again.";
		}
		$tag_name = $_POST["tag_name"];
		$description = $_POST["description"];
		$url = $_POST["url"];
		$url_title = $_POST["url_title"];
		$latitude = $_POST["latitude"];
		$longitude = $_POST["longitude"];
		$id = $_POST["id"];
		$success = $success and $driver->editTag($tag_name, $description, $latitude, $longitude, $target_path, $url, $url_title, $id);
		if ($success){
			header("Location: tags.php?msg=1");
		} else {
			header("Location: tags.php?msg=2");
		}
		exit();
	}
	$array = $driver->getTag($_GET["edit"]);
	$tag_name = $array["point_name"];
	//$uploadedfile = $array["image_path"];
	$description = $array["description"];
	$url = $array["url"];
	$url_title = $array["text_url"];
	$latitude = $array["latitude"];
	$longitude = $array["longitude"];
	$id = $array["id"];
}
if($deleting){
	$success = $driver->deleteTag($_GET["delete"]);
	if ($success){
		header("Location: tags.php?msg=3");
	} else {
		header("Location: tags.php?msg=2");
	}
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    
    <title>Edit your tags : GeoDisplay</title>
    
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
                    tag_name : {
                        required : true, //para validar campo vacio
                    },
                    description :{
                        required : true	
                    },
					url :{
                        required : true	
                    },
					url_title :{
                        required : true	
                    }
                },
                messages :{
                    tag_name : {
                        required : "", //para validar campo vacio
                    },
                    description : {
                        required : ""	
                    },
					url :{
                        required : ""	
                    },
					url_title :{
                        required : ""	
                    }
                },
                errorElement: "div",
                wrapper: "div",  // a wrapper around the error message
                errorPlacement: function(error, element) {
                    offset = element.offset();
                    error.insertBefore(element)
                    //error.addClass('message-form');  // add a class to the wrapper
                    error.css('position', 'absolute');
                    error.css('left', offset.left + element.outerWidth() + 10);
                    error.css('top', offset.top + 5);
                }
            });
		
		$("textarea[maxlength]").keyup(function() {
        var limit   = $(this).attr("maxlength"); // Límite del textarea
        var value   = $(this).val();             // Valor actual del textarea
        var current = value.length;              // Número de caracteres actual
            if (limit < current) {                   // Más del límite de caracteres?
                // Establece el valor del textarea al límite
                $(this).val(value.substring(0, limit));
            }
        });
    });
	</script>
    
</head>
<body>
    <?php
        print_header();
		if($reading){
    ?>  
    	<div class="container">
			<div class="row">
				<div class="span10 offset1">
                <form class="form-horizontal" >
                    <legend>Edit your tags</legend>
                	<div class="row-fluid">
					<?php
					switch ($msg) {
					case 1:
					?>
						<div >
							<div class="alert alert-success alerta">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Great!</strong> You have successfully edited a tag.
							</div>
						</div>
					<?php
					break;
					case 2:
					?>
						<div >
							<div class="alert alert-error alerta">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Oh snap!</strong> Change a few things up and try submitting again.
							</div>
						</div>
					<?php
					break;
					case 3:
					?>
						<div >
							<div class="alert alert-success alerta">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Ok!</strong> You have successfully deleted a tag.
							</div>
						</div>
					<?php
					break;
					}
					$driver->getTags($_SESSION['id']);
					?>
                    </div>
                </form>
            </div>
        </div>
        </div>
	<?php
		} else {
	?> 
		<div class="container">
			<div class="row">
				<div class="span10 offset1">
                <form form action="tags.php?submit&edit=<?php echo $_GET["edit"]?>" enctype="multipart/form-data" class="form-horizontal" id="edit_tag_form" method="POST">
                    <legend>Edit your tag</legend>
                    <div class="row-fluid">
                        <div class="span5">
                            <label for="tag_name">Tag name *</label>
                            <input type="text" id="tag_name" name="tag_name" value="<?php echo $tag_name ?>" class="input-block-level" placeholder="My tag">
                        	
                            <label for="uploadedfile"><br>Image *</label>
							<input id="uploadedfile" name="uploadedfile" value="<?php echo $uploadedfile ?>" type="file">
                        
                            <label for="description" style="margin-top:10px;">Description *</label>
                            <textarea name="description" id="description" style="resize:none" maxlength="140" rows="4" class="input-block-level" placeholder="140 characters"><?php echo $description ?></textarea>
                        	
							<label for="url"><br>URL *</label>
							<input type="text" id="url" name="url" value="<?php echo $url ?>" class="input-block-level" placeholder="www.mycompany.com">
							
							<label for="url_title"><br>URL Title *</label>
							<input type="text" id="url_title" name="url_title" value="<?php echo $url_title ?>" class="input-block-level" placeholder="My company">
                        
                        </div>
                        <div class="span7">
                        	<div id="map" style="margin: 0; padding: 0;height:300px; width:auto;"></div>
							<input type="text" id="latitude" name="latitude" style="display: none;" value="<?php echo $latitude ?>" class="input-block-level" >
							<input type="text" id="longitude" name="longitude" style="display: none;" value="<?php echo $longitude ?>" class="input-block-level" >
							<input type="text" id="id" name="id" style="display: none;" value="<?php echo $id ?>" class="input-block-level" >

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
	<?php
		}
    ?> 
                
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.file-input.js"></script>
</body>
</html>