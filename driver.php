<?php
//error_reporting(0);
class dbDriver{
	private $conexion;
	
	function __construct(){
	    $this->conexion = mysqli_connect("localhost","root","","creatorsstudio");
	    if (mysqli_connect_errno())
	  	{
	  		echo "Error while connecting the database" . mysqli_connect_error();
	  	}
		session_start();
	}
	
	function __destruct(){
		mysqli_close($this->conexion);
	}

	function addTag($user_id, $point_name, $description, $latitude, $longitude, $image_path, $url, $logo_path, $audio_path, $video_path, $facebook, $twitter){
		$query = mysqli_query($this->conexion, "INSERT INTO points (user_id, point_name, description, latitude, longitude, image_path, url, audio_path, video_path) VALUES ('$user_id', '$point_name', '$description', '$latitude', '$longitude', '$image_path', '$url', '$audio_path', '$video_path')");
		$query = mysqli_query($this->conexion, "UPDATE users SET logo_path = '$logo_path' WHERE id='$user_id' ");
		$query = mysqli_query($this->conexion, "UPDATE users SET facebook = '$facebook' WHERE id='$user_id' ");
		$query = mysqli_query($this->conexion, "UPDATE users SET twitter = '$twitter' WHERE id='$user_id' ");
	}
	
	function login($user, $password){
		$password = md5($password);
		$query = mysqli_query($this->conexion,"SELECT * from users where email='$user'");			
		$row = $query->fetch_array(MYSQLI_ASSOC);
		if($row['password'] == $password){
			$_SESSION["name"] = $row["name"];
			$_SESSION["id"] = $row["id"];
			header('Location: index.php');
		} else {
			header('Location: login.php?err=1');
		}
	}
	
	function verify($username){
		if($username != $_SESSION["id"]){
			header('Location: login.php?err=2');
		}
	}
	
	function getTag($id){
		$query = mysqli_query($this->conexion, "SELECT * FROM points WHERE id='$id'");
		$row=$query->fetch_array(MYSQLI_ASSOC);
		$array = array(
			"point_name" => $row['point_name'],
			"description" => $row['description'],
			"latitude" => $row['latitude'],
			"longitude" => $row['longitude'],
			"image_path" => $row['image_path'],
			"url" => $row['url'],
			"text_url" => $row['text_url'],
			"id" => $row['id'],
		);
		return $array;
	}

	function getTags($username){
		$query = mysqli_query($this->conexion, "SELECT * FROM points WHERE user_id='$username' order by id");
		echo '<table class="table zebra-striped">';
		echo "<thead><tr><th>#</th><th>Tag name</th><th>URL</th><th>Latitude</th><th>Longitude</th><th>Edit</th><th>Delete</th></tr></thead>";
		$i = 1;
		echo "<tbody>";
		while($row=$query->fetch_array(MYSQLI_ASSOC)){
			echo "<tr><th>".$i."</th><th>".$row['point_name']."</th><th>".$row['url']."</th><th>".$row['latitude']."</th><th>".$row['longitude']."</th><th><a href='tags.php?edit=".$row['id']."' class='btn btn-info btn-small'>Edit</a></th><th><a href='tags.php?delete=".$row['id']."' class='btn btn-danger btn-small'>Delete</a></th></tr>";
			$i++;
		}
		echo "</tbody>";
		echo "</table>";
	}
	
	function editTag($point_name, $description, $latitude, $longitude, $image_path, $url, $text_url, $id){
		if($image_path=="uploads/"){
			return $query = mysqli_query($this->conexion,"UPDATE points SET point_name='$point_name', description='$description', latitude=$latitude, longitude=$longitude, url='$url', text_url='$text_url' WHERE id='$id'");
		} else {
			return $query = mysqli_query($this->conexion,"UPDATE points SET point_name='$point_name', description='$description', latitude=$latitude, longitude=$longitude, image_path='$image_path', url='$url', text_url='$text_url' WHERE id='$id'"); 	 
		}
	}
	
	function editTagWithoutImage($point_name, $description, $latitude, $longitude, $url, $text_url, $id){
		return $query = mysqli_query($this->conexion,"UPDATE points SET point_name='$point_name', description='$description', latitude=$latitude, longitude=$longitude, url='$url', text_url='$text_url' WHERE id='$id'");
	}
	
	function deleteTag($id){
		return $query = mysqli_query($this->conexion,"DELETE FROM points WHERE id='$id'");
	}
	
	function getTagsByUser($id){
		$sql_tag  = mysqli_query($this->conexion,"SELECT * FROM points where user_id='$id'");
		$sql_user = mysqli_query($this->conexion,"SELECT * FROM users where id='$id'");
		$row_user = mysqli_fetch_array($sql_user);
		$response = array();
		$posts = array();
		while($row_tag = mysqli_fetch_array($sql_tag)) {
			$point_name = $row_tag['point_name'];
			$description = $row_tag['description'];
			$latitude = $row_tag['latitude'];
			$longitude = $row_tag['longitude'];
			$image_path = $row_tag['image_path'];
			$url = $row_tag['url'];
			$audio_path = $row_tag['audio_path'];
			$video_path = $row_tag['video_path'];
			$facebook = $row_user['facebook'];
			$twitter = $row_user['twitter'];

			$posts[] = array('point_name'=>$point_name, 'description'=>$description, 'latitude'=>$latitude, 'longitude'=>$longitude, 'image_path'=>$image_path, 'url'=>$url, 'audio_path'=>$audio_path, 'video_path'=>$video_path, 'facebook'=>$facebook , 'twitter'=>$twitter);
		}
		$response['posts'] = $posts;
		$fp = fopen("$id.json", 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}

	function checkPoints($id){
		$query = mysqli_query($this->conexion, "SELECT points FROM users where id='$id'");
		$row=$query->fetch_array(MYSQLI_ASSOC);
		if($row['points'] > 0){
			return true;
		} else {
			return false;
		}
	}
}
?>