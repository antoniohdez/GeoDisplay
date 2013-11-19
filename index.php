<?php 

require_once("driver.php");
require_once("layout.php");
$driver = new dbDriver();

if(!isset($_SESSION["id"])){
	header('Location: login.php');
} 

$msg = isset($_GET["msg"]) ? $_GET["msg"] : 0;
$tag_name = '';
$uploadedfile = '';
$description = '';
$url = '';
$url_title = '';
$latitude = '';
$longitude = '';
$success = true;
$canUpload = $driver->checkPoints($_SESSION["id"]);

if(isset($_GET["submit"])){
	if($canUpload){
		$target_path = "uploads/";
		$target_path = $target_path.basename($_FILES['uploadedfile']['name']); 
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) { 
			//echo "File ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
			$success = true;
		} else{
			//echo "Something is wrong, try again. Please.";
			$success = false;
		}

        $logo_path = "logo/";
        $logo_path = $logo_path.basename($_FILES['uploadedlogo']['name']); 
        if(move_uploaded_file($_FILES['uploadedlogo']['tmp_name'], $logo_path)) { 
            $success = true;
        } else{
            $success = false;
        }

		$tag_name = $_POST["tag_name"];
		$description = $_POST["description"];
		$url = $_POST["url"];
		$url_title = $_POST["url_title"];
		$latitude = $_POST["latitude"];
		$longitude = $_POST["longitude"];
		//$success = $success and 
        $driver->addTag($_SESSION["id"], $tag_name, $description, $latitude, $longitude, $target_path, $url, $url_title , $logo_path);
		
        if ($success){
			header("Location: index.php?msg=1");
		} else {
			header("Location: index.php?msg=2");
		}
	} else {
		header("Location: index.php?msg=3");
	}
	exit();
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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="js/map.js"></script>
	<script src="js/jquery.js"></script>
    <script src="js/jquery.validate.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#tag_form").validate({
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
                    },
					uploadedfile :{
                        required : true	
                    },
                    uploadedlogo :{
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
                    },
					uploadedfile :{
                        required : ""	
                    },
                    uploadedlogo :{
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
    ?>  

    	<div class="container">
			<div class="row">
				<div class="span10 offset1">
                <form form action="index.php?submit" enctype="multipart/form-data" class="form-horizontal" id="tag_form" method="POST">
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
					}
					?>
                    <div class="row-fluid">
                        <div class="span5">
                            <label for="tag_name">Tag name *</label>
                            <input type="text" id="tag_name" name="tag_name" value="<?php echo $tag_name ?>" class="input-block-level" placeholder="My tag">
                        	
                            <label for="uploadedfile"><br>Image *</label>
							<input id="uploadedfile" name="uploadedfile" type="file" onchange="upload_img(this);" />

                            <label for="uploadedlogo"><br>Logo *</label>
                            <input id="uploadedlogo" name="uploadedlogo" type="file">
                            
                            <!--
							<label for="uploadedfile">Imagen *</label>
							<a class="file-input-wrapper btn">Search for an image<input type="file" title="Search for a file to add"></a>
                            -->
                        
                            <label for="description" style="margin-top:10px;">Description *</label>
                            <textarea id="description" name="description" style="resize:none" maxlength="140" rows="4" value="<?php echo $description ?>" class="input-block-level" placeholder="140 characters"></textarea>
                        	
							<label for="url"><br>URL *</label>
							<input type="text" id="url" name="url" value="<?php echo $url ?>" class="input-block-level" placeholder="www.mycompany.com">
							
							<label for="url_title"><br>URL Title *</label>
							<input type="text" id="url_title" name="url_title" value="<?php echo $url_title ?>" class="input-block-level" placeholder="My company">
                        
                        </div>
                        <div class="span7">
                        	<label for="latitude">Latitude *</label>
                            <input type="text" id="latitude" name="latitude" class="input-block-level" >
                            
                            <label for="longitude"><br>Longitude *</label>
                            <input type="text" id="longitude" name="longitude" class="input-block-level" >

                            <div id="map" style="margin: 15px 0 0 0; padding: 0;height:230px; width:auto;"></div>
							<div class="preview">
                                <div id="previewImage" style="float:left; width:80px; height:80px; background:#DDD">
                                   <img id="img_id" width="80px" height="80px" src="#" /> 
                                </div>
                                <div style="margin-left:90px;">
                                    <div id="preTitle">
                                    
                                    </div>
                                    <div id="preDescription">
                                    
                                    </div>
                                    <div>
                                    
                                    </div>
                                </div>
                            </div>
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
        $("input").on("focusout", function() {
            $("#preTitle").html($("#tag_name").val());
        });
        $("textarea").on("focusout", function() {
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


    </script>
</body>
</html>