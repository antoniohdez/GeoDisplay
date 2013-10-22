<?php	
	
class dbDriver{
	private $conexion;
	
	function __construct(){
	    $this->conexion = mysql_connect("127.0.0.1:3306", "root", "") or die("Error while connecting the database");
		mysql_select_db("creatorsstudio");
		session_start();
	}
	
	function __destruct(){
		mysql_close($this->conexion);
	}

	function addTag($user_id, $point_name, $description, $latitude, $longitude, $image_path, $url, $text_url, $id){
		return $query = mysql_query("INSERT INTO points VALUES('$user_id', '$point_name', '$description', $latitude, $longitude, '$image_path', '$url', '$text_url', $id)");
	}
	
	function login($user, $password){
		$password = md5($password);
		$query = mysql_query("SELECT * from users where email='$user'");			
		$row = mysql_fetch_array($query);
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
		$query = mysql_query("SELECT * FROM points WHERE id='$id'");
		$row=mysql_fetch_array($query);
		$array = [
			"point_name" => $row['point_name'],
			"description" => $row['description'],
			"latitude" => $row['latitude'],
			"longitude" => $row['longitude'],
			"image_path" => $row['image_path'],
			"url" => $row['url'],
			"text_url" => $row['text_url'],
			"id" => $row['id'],
		];
		return $array;
	}

	function getTags($username){
		$query = mysql_query("SELECT * FROM points WHERE user_id='$username' order by id");
		echo '<table class="table zebra-striped">';
		echo "<thead><tr><th>#</th><th>Tag name</th><th>URL</th><th>Latitude</th><th>Longitude</th><th>Edit</th><th>Delete</th></tr></thead>";
		$i = 1;
		echo "<tbody>";
		while($row=mysql_fetch_array($query)){
			echo "<tr><th>".$i."</th><th>".$row['point_name']."</th><th>".$row['url']."</th><th>".$row['latitude']."</th><th>".$row['longitude']."</th><th><a href='tags.php?edit=".$row['id']."' class='btn btn-info btn-small'>Edit</a></th><th><a href='tags.php?delete=".$row['id']."' class='btn btn-danger btn-small'>Delete</a></th></tr>";
			$i++;
		}
		echo "</tbody>";
		echo "</table>";
	}
	
	function editTag($point_name, $description, $latitude, $longitude, $image_path, $url, $text_url, $id){
		if($image_path=="uploads/"){
			return $query = mysql_query("UPDATE points SET point_name='$point_name', description='$description', latitude=$latitude, longitude=$longitude, url='$url', text_url='$text_url' WHERE id='$id'");
		} else {
			return $query = mysql_query("UPDATE points SET point_name='$point_name', description='$description', latitude=$latitude, longitude=$longitude, image_path='$image_path', url='$url', text_url='$text_url' WHERE id='$id'"); 	 
		}
	}
	
	function editTagWithoutImage($point_name, $description, $latitude, $longitude, $url, $text_url, $id){
		return $query = mysql_query("UPDATE points SET point_name='$point_name', description='$description', latitude=$latitude, longitude=$longitude, url='$url', text_url='$text_url' WHERE id='$id'");
	}
	
	function deleteTag($id){
		return $query = mysql_query("DELETE FROM points WHERE id='$id'");
	}
	
	function getTagsByUser($id){
		$sql = mysql_query("SELECT * FROM points where user_id='$id'");
		$response = array();
		$posts = array();
		while($row=mysql_fetch_array($sql)){
			$point_name = $row['point_name'];
			$description = $row['description'];
			$latitude = $row['latitude'];
			$longitude = $row['longitude'];
			$image_path = $row['image_path'];
			$url = $row['url'];
			$text_url = $row['text_url'];
			
			$posts[] = array('point_name'=> $point_name, 'description'=> $description, 'latitude'=> $latitude, 'longitude'=> $longitude, 'image_path'=> $image_path, 'url'=> $url, 'text_url'=> $text_url);
		}
		$response['posts'] = $posts;
		$fp = fopen("$id.json", 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}
	
	function checkPoints($id){
		$query = mysql_query("SELECT points FROM users where id='$id'");
		$row=mysql_fetch_array($query);
		if($row['points'] > 0){
			return true;
		} else {
			return false;
		}
	}
}
?>