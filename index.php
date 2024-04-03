<?php
    require_once('PHP/config.php');
	require_once('PHP/handlers.php');
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Title -->
    <title>Talent Hub || 01CV </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="CSS/style.css" type="text/css">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="image/favicon.ico">

    <!-- Template -->
    <link rel="stylesheet" href="public/css/index.css">
  </head>

  <body class="up">
  <header>
			<div class="brand text-center mb-3">
				<img fetchpriority="high" decoding="async" class="logo" width="500" height="208" src="https://zone01rouennormandie.org/wp-content/uploads/2024/01/logo-01TN-Blanc.png" alt="Logo 01Talent Normandie Blanc" title="logo-01TN-Blanc" srcset="https://zone01rouennormandie.org/wp-content/uploads/2024/01/logo-01TN-Blanc.png 500w, https://zone01rouennormandie.org/wp-content/uploads/2024/01/logo-01TN-Blanc-300x125.png 300w" sizes="(max-width: 500px) 100vw, 500px" class="wp-image-15024">
				<div>
					<h5 class="cold">Explorer → Découvrir → Interview l'internant → Décider → Complétion de la fiche Entreprise → Signature du contrat → Depot du dossier á zone01🤝</h5>
				</div>
			</div>			
	</header>

	<!-- Preloader Gif -->
	<table class="doc-loader">
		<tr>
			<td>
				<img fetchpriority="high" decoding="async" class="logo" width="500" height="208" src="https://zone01rouennormandie.org/wp-content/uploads/2024/01/logo-01TN-Blanc.png" alt="Logo 01Talent Normandie Blanc" title="logo-01TN-Blanc" srcset="https://zone01rouennormandie.org/wp-content/uploads/2024/01/logo-01TN-Blanc.png 500w, https://zone01rouennormandie.org/wp-content/uploads/2024/01/logo-01TN-Blanc-300x125.png 300w" sizes="(max-width: 500px) 100vw, 500px" class="wp-image-15024">
			</td>
		</tr>
	</table>

    <main class="main" class="brand" style="display: none;">
		<div class="card mid_box" id="hunned">
			<div class="card-body">
				<h4 class="brand text-center mb-3 blue_bg" alt="middle div has text not decided yet">préférences ?</h4>
				<div class="input-wrapper">
					<input type="text" class="nosubmit" id="searchBox" name="txtBox" onkeyup="search()" placeholder=" Quel profile recherchez-vous ?">
				</div>
				<div class="button-wrapper">
					<select id="profileSelector" name="profile_id" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterByProfile()" -->
					<option value="">Spécialisation</option>
						<?php getProfile(); ?>
					</select>
					<i class="arrow down"></i>
					<select id="skillSelector" name="skill_id" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterBySkill()" -->
						<option value="">Skills</option>
						<?php getSkills() ?>
					</select>
					<i class="arrow down"></i>
					<select id="availabilitySelector" name="dispo_sid" class="filter-select">
						<option value="">Disponibilité</option>
						<option value="option1">Option 1</option>
						<option value="option2">Option 2</option>
						<option value="option3">Option 3</option>
						<option value="option4">Option 4</option>
						<option value="option5">Option 5</option>
						<option value="option6">Option 6</option>
					</select>
					<i class="arrow down"></i>
					<select id="locationSelector" name="locate_sid" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterLocation()" -->
						<option value="">Lieu</option>
						<option value="Tout la France">Tout la France</option>
						<?php getLieu(); ?>
					</select>
					<i class="arrow down"></i>
				</div>

			</div>
		</div>
		<div id="box">
			<div class="not-found">
				<p>AUCUN RÉSULTAT TROUVÉ</p>
			</div>
		<div class="content">

			<div id="studentCard" class="container-fluid pb-5">

				<div class="row">
						<?php
							$pdo = getPDO();
							$query = "SELECT s.id, s.nom, s.prenom, s.location, s.anywhere, s.mail, p.profile_one, p.profile_two, d.skill_one, d.skill_two, d.skill_three, d.skill_four, u.image_path, COUNT(s.id) AS number_of_students FROM students s
							LEFT JOIN profiles p ON s.id = p.student_id
							LEFT JOIN skills d ON s.id = d.student_id
							LEFT JOIN uploads u ON s.prenom = u.student_name
							GROUP BY p.id, s.nom ORDER BY p.id";
							$stmt = $pdo->query($query);
							$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
							if(count($result) > 0)
								{
									foreach($result as $display)
									{
						?>

						<div class="col-12 col-md-3 student-card">
							<div class="card mb-3" id="borders">

								<div class="card-body">
									<div>
										<!-- $$$$$$$$$$$******************###########################******************$$$$$$$$$$$$$$$$$$$**************** -->
										<img alt="Profile Picture" width="140" height="140" class="styleImg" <?= ($display["image_path"] == NULL) ? 'id="uploadedImage" src="https://via.placeholder.com/300x300?text=Profile+Picture"' : 'src="' . "ibukun/". $display["image_path"] . '" alt="Uploaded Image"' ?>>

										<h3 class="space"><?= $display['prenom'] ?> <br> <?= $display['nom'] ?></h3>
									</div><br>
										<p class="inline-buttons">
											<span class="btn small-btn"><?= $display['profile_one'] ?></span>
											<span class="btn small-btn"><?= $display['profile_two'] ?></span>
										</p>
										<p id="skill_number">⚙️ <?= $display['skill_one'] ?>, <?= $display['skill_two'] ?>, <?= $display['skill_three'] ?>, <?= $display['skill_four'] ?></p>
										<p>📍 Disponible pour travail á <span id="bold_text"><?= $display['location'] ?><?= ($display['anywhere'] == 1) ? ", Tout la France." : "." ?></span></p>
										<p id="bold_text"> 📩 <?= $display['mail'] ?></p>
										<button type="button" onclick="viewVideo('<?=$display['prenom'];?>')" class="viewPromoBtn btn btn-primary btn-sm">Vidéo 📹</button>
										<button type="button" value="<?=$display['id'];?>" class="editPromoBtn btn btn-primary btn-sm">Portfolio 🗒</button>
										<button type="button" value="<?=$display['id'];?>" class="deletePromoBtn btn btn-info btn-sm">Recommendation 🖊️</button>
									<!-- </div> -->
								</div>

								<div class="container-fluid">
									<div class="footer-content text-center small">
										<p>Aller à <span class="intra" onclick="location.href='route123/reg.php'">l'espace planning du  CSM</span></p>
									</div>
								</div>
							</div>
						</div><br>
						<?php
								}
							}
						?>
					</div>
						<!-- <footer class="footer mt-3">
							<div class="container-fluid">
								<div class="footer-content text-center small">
                                <p>Aller à <span class="intra" onclick="location.href='https://planning-campus-saint-marc.hyperplanning.fr/hp/'">l'espace planning du  CSM</span></p>
									<span class="text-muted">&copy; 2019 Graindashboard. All Rights Reserved.</span>
								</div>
							</div>
						</footer> -->
				</div>
			</div>

      	</div>
		</div>
    </main>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="PHP/JS/handlers.js"></script>
	<script>
	$(document).ready(function() {
		$('.doc-loader').css('background-color', '#001940').fadeOut(1800, function() {
			$('.main').fadeIn('slow');
		});
	});
	</script>
  </body>
</html>