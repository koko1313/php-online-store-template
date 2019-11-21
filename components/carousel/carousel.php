<?php include "components/db_config.php" ?>

<div id="slideshow">
	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		
		<?php
			$result_carousel = $db->query("SELECT * FROM carousel");
		?>
		
		<ol class="carousel-indicators">
			<?php
				$active = "class='active'";
				for ($i=0; $i<$result_carousel->num_rows; $i++) {
					echo '<li data-target="#carouselExampleControls" data-slide-to="'. $i .'" '. $active .'></li>';
					$active = "";
				}
			?>
		</ol>

		<div class="carousel-inner">
			<?php
				if ($result_carousel->num_rows > 0) {
					$active = "active";
					if(!isset($directory_in)) $directory_in = "";
					while($row_carousel = mysqli_fetch_array($result_carousel)) {
						echo '
							<div class="carousel-item '. $active .'">
								<img class="d-block w-100" src="'. $directory_in .'components/carousel/images/'. $row_carousel["image"] .'" alt="'. $row_carousel["description"] .'">
								<div class="carousel-caption d-none d-md-block">
									<h5>'. $row_carousel["title"] .'</h5>
									<p>'. $row_carousel["description"] .'</p>
								</div>
						';
							if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") {
								echo '
									<div class="carousel-img-edit">
										<a href="components/carousel/carousel_form.php?edit=1&id='. $row_carousel["id"] .'"><i class="fas fa-edit"></i></a>
										<a href="components/carousel/carousel_form.php?delete=1&id='. $row_carousel["id"] .'"><i class="fas fa-trash-alt"></i></a>
									</div>
								';
							}
						echo '	
							</div>
						';
						$active = "";
					}
				} else {
					echo '
						<div class="carousel-item active">
							<div style="height: 350px"></div>
						</div>
					';
				}
			?>
		</div>
	
    </div>
	  
	<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
	
	<?php if(isset($_SESSION["email"]) && $_SESSION["role"] == "admin") { ?>
		<div id="edit-carousel">
			<a href="components/carousel/carousel_form.php">Добави снимки в слайдшоуто</a>
		</div>
	<?php } ?>
</div>

<style>
#slideshow {
	padding: 0;
	display: block;
	position: relative;
	max-height: 350px;
}

.carousel-item img {
	max-height: 350px!important;
}

.carousel-img-edit {
	position: absolute;
	bottom: 10px;
	right: 20%;
	font-size: 1.5em;
}

#edit-carousel {
	position: absolute;
	top: 10px;
	right: 10px
}
</style>