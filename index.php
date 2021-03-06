<?php 

require_once("driver.php");
require_once("layout.php");
$driver = new dbDriver();

if(!isset($_SESSION["id"])){
	header('Location: login.php');
} 

$msg = isset($_GET["msg"]) ? $_GET["msg"] : 0;
$tag_name = $description = $url = $latitude = $longitude = $facebook = $twitter = '';

$canUpload = $driver->checkPoints($_SESSION["id"]);

if(isset($_GET["submit"])){
	if($canUpload){
		$target_path = "uploads/";
		$target_path = $target_path.basename($_FILES['uploadedfile']['name']); 
		if(! move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) { 
			header("Location: index.php?msg=2");
		}

        $audio_path = "audio/";
        $audio_path = $audio_path.basename($_FILES['uploadedaudio']['name']); 
        if(! move_uploaded_file($_FILES['uploadedaudio']['tmp_name'], $audio_path)) { 
            header("Location: index.php?msg=2");
        } 

        $video_path = "video/";
        $video_path = $video_path.basename($_FILES['uploadedvideo']['name']);
        if(! move_uploaded_file($_FILES['uploadedvideo']['tmp_name'], $video_path)) { 
            header("Location: index.php?msg=2");
        }

		$tag_name = $_POST["tag_name"];
		$description = $_POST["description"];
		$url = $_POST["url"];
		$latitude = $_POST["latitude"];
		$longitude = $_POST["longitude"];
        $facebook = $_POST["facebook"];
        $twitter = $_POST["twitter"];

        $driver->addTag($_SESSION["id"], $tag_name, $description, $latitude, $longitude, $target_path, $url, $audio_path, $video_path, $facebook, $twitter);
		
		header("Location: index.php?msg=1");
	} 
    else {
		header("Location: index.php?msg=3");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add a tag : GeoDisplay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/register_form.css" rel="stylesheet">
    <link href="css/general_style.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    <script src="js/map.js"></script>
	<script src="js/jquery.js"></script>
    <script src="js/jquery.validate.js"></script>
	<script type="text/javascript">
    var validator;
	$(document).ready(function() {
		validator = $("#tag_form").validate({
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
        var limit = $(this).attr("maxlength"); 
        var value = $(this).val();             
        var current = value.length;              
            if (limit < current) {
                $(this).val(value.substring(0, limit));
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
                <form form action="index.php?submit" enctype="multipart/form-data" class="form-horizontal" id="tag_form" method="POST">
                    <div class="modalLoading"></div>
                    <legend>Add a tag</legend>
					<?php
					switch ($msg) {
					case 1:
						?>
						<div >
							<div class="alert alert-success alerta">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Great!</strong> You have successfully registered a new tag.
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
							<div class="alert alert-error alerta">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong>Oh snap!</strong> You do not have enough points.
							</div>
						</div>
						<?php
						break;
                    case 6:
                        ?>
                        <div >
                            <div class="alert alert-success alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Great!</strong> You have successfully edited your profile information.
                            </div>
                        </div>
                        <?php
                        break;
                    case 7:
                        ?>
                        <div >
                            <div class="alert alert-error alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Oh snap!</strong> Your image couldn't load correctly.
                            </div>
                        </div>
                        <?php
                        break;
                    case 8:
                        ?>
                        <div >
                            <div class="alert alert-error alerta">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Oh snap!</strong> Your information couldn't load correctly.
                            </div>
                        </div>
                    <?php
                        break;
					}
					?>
                    <div class="row-fluid">
                        <div class="span5">

                            <label for="tag_name">Tag name *</label>
                            <input type="text" id="tag_name" name="tag_name" value="<?php echo $tag_name ?>" class="input-block-level" placeholder="My tag">
                        
                            <label for="description" style="margin-top:10px;">Description *</label>
                            <textarea id="description" name="description" style="resize:none" maxlength="140" rows="4" value="<?php echo $description ?>" class="input-block-level" placeholder="140 characters"></textarea>
                        	
							<label for="url"><br>URL *</label>
							<input type="text" id="url" name="url" value="<?php echo $url ?>" class="input-block-level" placeholder="www.mycompany.com">

                            <label for="facebook"><br>Facebook *</label>
                            <input type="text" id="facebook" name="facebook" value="<?php echo $facebook ?>" class="input-block-level" placeholder="Facebook account">

                            <label for="twitter"><br>Twitter *</label>
                            <input type="text" id="twitter" name="twitter" value="<?php echo $twitter ?>" class="input-block-level" placeholder="Twitter account">

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
                            <input type="text" id="latitude" name="latitude" class="input-block-level" >
                            
                            <label for="longitude"><br>Longitude *</label>
                            <input type="text" id="longitude" name="longitude" class="input-block-level" >

                            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                            <div id="map" style="margin: 15px 0 0 0; padding: 0;height:400px; width:auto;"></div>
							<!--
                            <div class="preview">
                                <div id="previewImage" style="float:left; width:80px; height:80px; background:#DDD">
                                   <img id="img_id" width="80px" height="80px" src="#" /> 
                                </div>
                                <div style="margin-left:90px;">
                                    <div id="preTitle"></div>
                                    <div id="preDescription"></div>
                                </div>
                            </div>
                            -->
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
        
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.file-input.js"></script>
    <script type="text/javascript">
        $("#tag_name").on("focusout", function() {
            $("#preTitle").html($("#tag_name").val());
        });
        $("#description").on("focusout", function() {
            $("#preDescription").html($("#description").val());
        });
        $('#img_id').hide();
        function upload_img(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_id').attr('src', e.target.result);
                }
                
                $('#img_id').show();

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#latitude").on("focusout", function() {
            pos = new google.maps.LatLng(parseFloat($("#latitude").val()), parseFloat($("#longitude").val()));
            marker.position = pos;
            map.setCenter(pos);
            openInfoWindow();
        });
        $("#longitude").on("focusout", function() {
            pos = new google.maps.LatLng(parseFloat($("#latitude").val()), parseFloat($("#longitude").val()));
            marker.position = pos;
            map.setCenter(pos);
            openInfoWindow();
        });

        $("#tag_form").submit(function(e){
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

        $('#pac-input').keypress(function(e){
            if ( e.which == 13 ) e.preventDefault();
            if ( e.which == 13 ) return false;
        });
    </script>

</body>
</html>