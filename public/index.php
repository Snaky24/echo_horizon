<?php $niveau = "../"; ?>
<?php include($niveau . "public/liaisons/php/config.inc.php"); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keyword" content="">
	<meta name="author" content="">
	<meta charset="utf-8">
	<link rel="stylesheet" href="../public/liaisons/css/styles.css">
	<link rel="stylesheet" href="../ressources/liaisons/scss/styles.scss">
	<title>Un beau titre ici!</title>
	<?php include($niveau . "public/liaisons/fragments/headlinks.inc.php"); ?>
</head>

<body>
	<?php include($niveau . "public/liaisons/fragments/entete.inc.php"); ?>

	<main>
		<link rel="stylesheet" href="../public/liaisons/fragments/entete.inc.php">
		<span class="centered" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">Festival OFF de Québec</span>
		<span class="centered_dates" style="text-shadow: 1px 1px 2px #000, 0 0 40em #000;">DU 8 AU 11 JUILLET</span>
		<img class="image_festi" src="../public/liaisons/images/Rectangle 61.png" alt="Description">
		<nav class="nav_sec">
			<ul class="nav-sec__liste">
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Lieux</a></li>
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Tarifs</a></li>
				<li class="nav-sec__listeItem"><a href="<?php echo $niveau; ?>#" class="nav-sec__lien">Contact</a></li>
			</ul>
		</nav>
		<hr class="separator">
		<div id="contenu" class="conteneur">

			<?php
			$requeteSQL = "Select titre from actualites";
			$objStat = $objPdo->prepare($requeteSQL);
			$objStat->execute();
			$arrActualite = $objStat->fetchAll();
			foreach ($arrActualite as $actualite) {
				echo $actualite["titre"]; ?><BR>
			<?php } ?>

			<section>
				<h3>Entête de section</h3>
				<article>
					<header>
						<h4>Entête d'article</h4>
					</header>
					<p>Lorem ipsum dolor HTML5 nunc aut nunquam sit amet, consectetur adipiscing elit. Vivamus at est
						eros, vel fringilla urna.</p>
					<p>Per inceptos himenaeos. Quisque feugiat, justo at vehicula pellentesque, turpis lorem dictum
						nunc.</p>
					<footer>
						<h5>Pied d'article</h5>
					</footer>
				</article>
				<article>
					<header>
						<h4>Entête d'article</h4>
					</header>
					<p>Lorem ipsum dolor nunc aut nunquam sit amet, consectetur adipiscing elit. Vivamus at est eros,
						vel fringilla urna. Pellentesque odio</p>
					<footer>
						<h5>Pied d'article</h5>
					</footer>
				</article>
			</section>
		</div>


		<p><a href="#" class="bouton">Bouton</a></p>
		<p><a href="#" class="bouton--inverse">Bouton</a></p>
		<a href="#" class="hyperlien">lien test!</a>
	</main>

	<aside>
		<h3>Barre latérale</h3>
		<p>Lorem ipsum dolor nunc aut nunquam sit amet, consectetur adipiscing elit. Vivamus at est eros, vel fringilla
			urna. Pellentesque odio rhoncus</p>
	</aside>
	<hr class="separator">

	<?php include($niveau . "public/liaisons/fragments/piedDePage.inc.php"); ?>

</body>

</html>