<?php
// DB connection
include('connect-db/db_connect.php');

$sql = 'SELECT * FROM pizzas ORDER BY created_at DESC';

$query = mysqli_query($conn, $sql);

$result = mysqli_fetch_all($query, MYSQLI_ASSOC);

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<div class="container">
		<div class='row'>
			<?php foreach ($result as $pizza): ?>
				<div class='col s4'>
					<!-- Format db created_at field -->
					<?php $timestamp = new DateTime($pizza['created_at']); ?>
					
						<h5 class='header'><?php echo htmlspecialchars($pizza['title']); ?></h5>
						<a href="<?php echo htmlspecialchars($pizza['email']) ?>"><?php echo htmlspecialchars($pizza['email']); ?></a>
						<div class='card horizontal'>
							<div class='card-stacked'>
								<div class="image-url">
									<img src="uploads/<?php echo $pizza['image_url']; ?>" alt="<?php echo $pizza['image_url'] ?>">
								</div>
								<div class='card-content'>
									<ul>
									<!-- use explode() function to convert ingredients to an array then loop thru it -->
									<?php foreach(explode(',', $pizza['ingredients']) as $ing): ?>
										<li><?php echo htmlspecialchars($ing); ?></li>
									<?php endforeach; ?>
									</ul>
								</div>
								<div class='card-action'>
								<p><?php echo $timestamp->format('F j, Y g:ia'); ?></p>
								</div>
							</div>
						</div>
					</div>
			<?php endforeach; ?>

		</div>
	</div>

	<?php include('templates/footer.php'); ?>

</html>