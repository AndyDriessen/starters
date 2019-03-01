<?php
include("inc/dbconnect.php");

// posted data from reviiew form

if(isset($_POST['review_Button']))
{
	// connect to db
	if(isset($_POST["review"]) & isset($_POST["rating"]))
	// get posted data
	$review = addslashes($_POST['review']);
	$hero_id = $_POST['heroid'];
	$rating = $_POST['rating'];
	if($review != "")
	{// the query
	$insertSQL = "INSERT into `rating` values (null, $hero_id, $rating, NOW(), '$review')";

	echo $insertSQL;
	// execute query
	$resultinsert = $conn->query($insertSQL) or die(mysqli_error($conn));
	
	header("location: index.php?teamName=$teamName&heroId=$hero_id");

	}
	else
	{
		$message = "pleas fill in the text area";
	}
}
else
{
	$message = "pleas fill in the text area";
}

//die("\$_GET['teamId'] = " . $_GET['teamId']);

// check if there's a teamId in the URL
if(isset($_GET['teamId']))
{
	// define SQL
	$selectHeroesSQL = "SELECT * FROM `hero` WHERE `teamId` = " .  $_GET['teamId'];
	// run qiery
	$resultHeroes= $conn->query($selectHeroesSQL) or die(mysqli_error($conn));

	$heroes = []; // equals $heroes = array()
	while($row = $resultHeroes->fetch_assoc()){
		$heroes[] = $row;
	}	
}
else
{
	// define SQL
	$selectHeroesSQL = "SELECT * FROM `hero`";
	// run qiery
	$resultHeroes= $conn->query($selectHeroesSQL);

	$heroes = []; // equals $heroes = array()
	while($row = $resultHeroes->fetch_assoc()){
		$heroes[] = $row;
	}	
}


// check if there's a teamId in the URL
if(isset($_GET['heroId']))
{
	$heroId = $_GET['heroId'];
	// define SQL
	$selectHeroSQL = "SELECT * FROM `hero` WHERE `heroId` = " .  $_GET['heroId'];
	// run qiery
	$resultHero= $conn->query($selectHeroSQL) or die(mysqli_error($conn));

	$selectedHero = $resultHero->fetch_assoc();

}

?> 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="DC Heroes">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>DC Heroes - Code Example Layout</title>
</head>
<body>

	<header id="header">
		<a href="index.php"><img id="Logo" src="images/Logo.jpg" /></a>
		
	</header>
	
	<div id="main-container">

		<div id="main-left">
			<ul id="regionList">
				<?php
				$sql= "SELECT * FROM team";
				$result= $conn->query($sql);

				while($row = $result->fetch_assoc()){
					echo "<li class='regioButton'>";
					echo "<a href=index.php?teamId=" . $row["teamId"] . ">";
					echo "<p>" . $row["teamName"] . "</p>";
					echo "<img class='regionImages' src='" . $row["teamImage"] . "'/>";
					echo "</a>";
					echo "</li>";
				}
				?>		
			</ul>	
		</div>

		<div id="main-center">
			<?php
			foreach($heroes as $key => $hero)
			{
				?>
				<div>
					<h3><?php echo $hero['heroName']; ?></h3>
					<a href="index.php?teamId=<?php echo $hero['teamId']; ?>&heroId=<?php echo $hero['heroId']; ?>"><img class="heroImg" src="<?php echo $hero['heroImage']; ?>" width="100px" />More info</a>
				</div>
				<?php
			}
			?>
		</div>
 
			
		<div id="main-right">
			<?php
			if(isset($selectedHero))
			{
				?>
				<h2><?php echo $selectedHero['heroName']; ?></h2>
				<img src="<?php echo $selectedHero['heroImage']; ?>" class="hero-image" />
				<div float="left"><?php echo $selectedHero['heroDescription']; ?>  </div> <br />
				<div><?php echo $selectedHero['heroPower']; ?>  </div>
				<?php
				?>
				<form method="post" action="index.php?teamId=<?php echo $selectedHero['teamId']; ?>&heroId=<?php echo $selectedHero['heroId']; ?>">
					<div>
						<br />
						pleas leave a review 
						<br />
						1: <input type="radio" name="rating" value="1" /><br />
						2: <input type="radio" name="rating" value="2" /><br />
						3: <input type="radio" name="rating" value="3" /><br />
						4: <input type="radio" name="rating" value="4" /><br />
						5: <input type="radio" name="rating" value="5" /><br />
						<textarea name="review"></textarea>
						

						
						<button type="submit" name="review_Button">Leave review</button>
						<input type="hidden" name="heroid" value="<?php echo $selectedHero['heroId']?>" />
					</div>
				</form>
				<div id="review">
					<?php	
					$sql = "SELECT * FROM rating WHERE heroId = " . $heroId  . "  ";
					$result = $conn->query($sql);
					while($review = $result->fetch_assoc())
					{
					?>
						<div class = "review"><p><?php echo $review["ratingReview"]; ?></p></div>
					<?php
					} 
					?>
				</div>
			<?php
			}
			?>
		</div>
			
	</div>

</body>
</html>