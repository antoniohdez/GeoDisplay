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

$tag_name = $description = $url = $latitude = $longitude = $facebook = $twitter = $id = '';
$success = true;

if($editing){
	if(isset($_GET["submit"])){
		$target_path = "uploads/";
		$target_path = $target_path.basename($_FILES['uploadedfile']['name']); 
		if(! move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) { 
			header("Location: tags.php?msg=5");
		} 
		$audio_path = "audio/";
        $audio_path = $audio_path.basename($_FILES['uploadedaudio']['name']); 
        if(! move_uploaded_file($_FILES['uploadedaudio']['tmp_name'], $audio_path)) { 
            header("Location: tags.php?msg=6");
        } 
        $video_path = "video/";
        $video_path = $video_path.basename($_FILES['uploadedvideo']['name']);
        if(! move_uploaded_file($_FILES['uploadedvideo']['tmp_name'], $video_path)) { 
            header("Location: tags.php?msg=7");
        }

		$tag_name = $_POST["tag_name"];
		$description = $_POST["description"];
		$url = $_POST["url"];
		$latitude = $_POST["latitude"];
		$longitude = $_POST["longitude"];
		$facebook = $_POST["facebook"];
		$twitter = $_POST["twitter"];
		$id = $_POST["id"];

		$success = $driver->editTag($tag_name, $description, $latitude, $longitude, $target_path, $audio_path, $video_path, $url, $facebook, $twitter, $id);
		if ($success){
			header("Location: tags.php?msg=1");
		} else {
			header("Location: tags.php?msg=2");
		}
	}

	$array = $driver->getTag($_GET["edit"]);
	$tag_name = $array["point_name"];
	$description = $array["description"];
	$url = $array["url"];
	$latitude = $array["latitude"];
	$longitude = $array["longitude"];
	$facebook = $array["facebook"];
	$twitter = $array["twitter"];
	$id = $array["id"];
}
if($deleting){
	$success = $driver->deleteTag($_GET["delete"]);
	if ($success){
		header("Location: tags.php?msg=3");
	} else {
		header("Location: tags.php?msg=4");
	}
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
    var validator;
	$(document).ready(function() {
		validator = $("#edit_tag_form").validate({
                highlight: function(element, errorClass) {
                    $(element).addClass("input-error");
                },
                 rules :{
                    tag_name : { required : true },
                    description : { required : true },
					url : { required : true },
					uploadedfile : { required : true	},
                    uploadedlogo : { required : true },
                    uploadedaudio : { required : true },
                    uploadedvideo : { required : true },
                    facebook : { required : true },
                    twitter : { required : true }
                },
                messages :{
                    tag_name : { required : "" },
                    description : { required : "" },
					url : { required : "" },
					uploadedfile : { required : "" },
                    uploadedlogo : { required : "" },
                    uploadedaudio : { required : "" },
                    uploadedvideo : { required : "" },
                    facebook : { required : "" },
                    twitter : { required : "" }
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
		
		$("textarea[maxlength]").keyup(function() {
        var limit   = $(this).attr("maxlength"); 
        var value   = $(this).val();             
        var current = value.length;              
            if (limit < current) {                   
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
								<strong>Oh snap!</strong> Your information couldn't load correctly.
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
					case 4:
                    ?>
                        <div >
                            <div class="alert alert-error alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Oh snap!</strong> The tag couldn't be deleted.
                            </div>
                        </div>
                    <?php
                    break;
                    case 5:
                    ?>
                        <div >
                            <div class="alert alert-error alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Oh snap!</strong> Your image couldn't load correctly.
                            </div>
                        </div>
                    <?php
                    break;
                    case 6:
                    ?>
                        <div >
                            <div class="alert alert-error alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Oh snap!</strong> Your audio couldn't load correctly.
                            </div>
                        </div>
                    <?php
                    break;
                    case 7:
                    ?>
                        <div >
                            <div class="alert alert-error alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Oh snap!</strong> Your video couldn't load correctly.
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
                    <div class="modalLoading"></div>
                    <legend>Edit your tag</legend>
                    <div class="row-fluid">
                        <div class="span5">

                            <label for="tag_name">Tag name *</label>
                            <input type="text" id="tag_name" name="tag_name" value="<?php echo $tag_name ?>" class="input-block-level">
                        
                            <label for="description" style="margin-top:10px;">Description *</label>
                            <textarea id="description" name="description" style="resize:none" maxlength="140" rows="4" value="<?php echo $description ?>" class="input-block-level"><?php echo $description ?></textarea>
                        	
							<label for="url"><br>URL *</label>
							<input type="text" id="url" name="url" value="<?php echo $url ?>" class="input-block-level">

                            <label for="facebook"><br>Facebook *</label>
                            <input type="text" id="facebook" name="facebook" value="<?php echo $facebook ?>" class="input-block-level">

                            <label for="twitter"><br>Twitter *</label>
                            <input type="text" id="twitter" name="twitter" value="<?php echo $twitter ?>" class="input-block-level" >

                            <legend><br>Media data</legend>

                            <label for="uploadedfile">Image *</label>
                            <input id="uploadedfile" name="uploadedfile" type="file" onchange="upload_img(this);" />

                            <label for="uploadedaudio"><br>Audio *</label>
                            <input id="uploadedaudio" name="uploadedaudio" type="file">

                            <label for="uploadedvideo"><br>Video *</label>
                            <input id="uploadedvideo" name="uploadedvideo" type="file">
                        
                        </div>
                        <div class="span7">
                        	<label for="latitude">Latitude *</label>
                            <input type="text" id="latitude" name="latitude" value="<?php echo $latitude ?>" class="input-block-level" >
                            
                            <label for="longitude"><br>Longitude *</label>
                            <input type="text" id="longitude" name="longitude" value="<?php echo $longitude ?>" class="input-block-level" >
                            <input type="text" id="id" name="id" style="display: none;" value="<?php echo $id ?>" class="input-block-level" >

                            <div id="map" style="margin: 15px 0 0 0; padding: 0;height:230px; width:auto;"></div>
							
                        </div>
                    </div>

                	<div class="row-fluid">
                    	<div class="span12" style="margin-top:15px;">
                    		<hr>
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
    <script type="text/javascript">
        $("#latitude").on("focusout", function() {
            pos = new google.maps.LatLng(parseFloat($("#latitude").val()), parseFloat($("#longitude").val()));
            marker.position = pos;
            map.setCenter(pos);
        });
        $("#longitude").on("focusout", function() {
            pos = new google.maps.LatLng(parseFloat($("#latitude").val()), parseFloat($("#longitude").val()));
            marker.position = pos;
            map.setCenter(pos);
        });

        $("#edit_tag_form").submit(function(e){
            if(validator.form()){
                var form = this;
                e.preventDefault();
                //
                $("body").addClass("loading");
                $(".modalLoading").show(); 
                //
                var a = setTimeout(function(){
                    form.submit();
                },1000);
            }
        });
    </script>
</body>
</html>