<?php 
	include('connect-db/db_connect.php');

	// if(isset($_GET['submit'])){
	// 	echo $_GET['email'] . '<br />';
	// 	echo $_GET['title'] . '<br />';
	// 	echo $_GET['ingredients'] . '<br />';
	// }

	// Form Error checking 
	if(isset($_POST['submit']) && isset($_FILES['image'])){

		// echo "<pre>";
		// print_r($_FILES['image']);
		// echo "</pre>";

		$img_name = $_FILES['image']['name'];
		$tmp_name = $_FILES['image']['tmp_name'];
		$img_size = $_FILES['image']['size'];
		$error = $_FILES['image']['error'];


		$errors = ['email' => '', 'title' => '', 'ingredients' => '', 'image' => ''];

		if(empty($_POST['email'])) {
			$errors['email'] = "Email can not be empty";
		} else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Must be valid Email";
		}
		
		if(empty($_POST['title'])) {
			$errors['title'] = "Title can not be empty";
		}else {
			$title = $_POST['title'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $title)) {
				$errors['title'] = "Title must be 0 to 10 chars.";
			}
		}

		if(empty($_POST['ingredients'])) {
			$errors['ingredients'] = "Ingredients can not be empty";
		} else {
			$ingredients = $_POST['ingredients'];
			if(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
				$errors['ingredients'] = "Ingredients must be comma seperated.";
			}
		}

		// Image validation
		if($error === 0) {
			if($img_size > 125000) {
				$errors['image'] = "File is too large.";
			} else {
				$img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
				$img_ext_lowercase = strtolower($img_ext);

				$allowed_exts = array('jpg', 'jpeg', 'png');

				if(in_array($img_ext_lowercase, $allowed_exts)) {
					$new_img_name = uniqid('IMG-', true) . '.' . $img_ext_lowercase;
					$img_upload_path = 'uploads/'. $new_img_name;
					move_uploaded_file($tmp_name, $img_upload_path);
				} else {
					$errors['image'] = "That file type is not allowed.";
				}
			}
		}


		// Insert form input into database
		if(!array_filter($errors)) {

			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$title = mysqli_real_escape_string($conn, $_POST['title']);
			$ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
			$image_url = mysqli_real_escape_string($conn, $_POST['image']);

			$sql = "INSERT INTO pizzas(email, title, ingredients, image_url) VALUES ('$email', '$title', '$ingredients', '$new_img_name')";

			if(mysqli_query($conn, $sql)) {
				header("Location: index.php");
			} else {
				echo "query error: " . mysqli_error($conn);
			}
		}

	} // end of POST check

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Add a Pizza</h4>
		<form class="white" action="add.php" method="POST" enctype="multipart/form-data">
			<label for="email">Your Email
			<input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
			<div style="color: red;"><?php echo $errors['email']; ?></div>
			</label> 
			<label for="title">Pizza Title
			<input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title']); ?>">
			<div style="color: red;"><?php echo $errors['title']; ?></div>
			</label>
			<label for="ingredients">Ingredients (comma separated)
			<input type="text" name="ingredients" value="<?php echo htmlspecialchars($_POST['ingredients']); ?>">
			<div style="color: red;"><?php echo $errors['ingredients']; ?></div>
			</label>
			<br>
			<label for="image">Upload Image
			<input type="file" name="image">
			<div style="color: red;"><?php echo $errors['image']; ?></div>
			</label>
			<br>

			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>