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

  <body>
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
				<!-- beauiful search bar working perfectly -->
				<!-- <div class="input-wrapper">
					<input type="text" class="nosubmit" id="searchBox" name="txtBox" onkeyup="search()" placeholder=" Quel profile recherchez-vous ?">
				</div> -->
				<div class="button-wrapper first-wrapper">
					<select id="contratSelector" name="contrat" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterByProfile()" -->
					<option value="">Type de contrat</option>
					<option value="stage">Stage</option>
					<option value="1 year apprenticeship">Alternance 1 an</option>
					<option value="2 ans alternance">Alternance 2 an</option>
					</select>
					<i class="arrow down"></i>
					<select id="modeleSelector" name="modele" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterBySkill()" -->
						<option value="">Modèle de travail</option>
						<option value="Présentiel">Presentiel 100%</option>
						<option value="Teletravail">Teletravail 100%</option>
						<option value="Hybrid">Hybrid</option>
					</select>
					<i class="arrow down"></i>
					<select id="departmentSelector" name="dep_id" onchange="filterStudents()" class="filter-select">
						<option value="">Departement</option>
						<?php getDepartment(); ?>
					</select>
					<i class="arrow down"></i>
				</div>

				<!-- // END -->
				<div class="button-wrapper">
					<select id="profileSelector" name="profile_id" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterByProfile()" -->
					<option value="">Spécialisation</option>
						<?php getProfile(); ?>
					</select>
					<i class="arrow down"></i>
					<select id="skillSelector" name="skill_id" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterBySkill()" -->
						<option value="">Skills</option>
						<?php getSkills(); ?>
					</select>
					<i class="arrow down"></i>
					<select id="availabilitySelector" name="dispo_sid" onchange="filterStudents()" class="filter-select">
						<option value=",">Disponibilité</option>
						<?php getDisponibility(); ?>
					</select>
					<i class="arrow down"></i>
					<select id="locationSelector" name="locate_sid" onchange="filterStudents()" class="filter-select"> <!-- onchange="filterLocation()" -->
						<option value="">Localisation</option>
						<option value="1">Europe</option>
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
				$query = "SELECT s.id, s.nom, s.prenom, s.location, s.contrats, s.europe, d.dep_id, d.department, a.disponibility, s.anywhere, s.teletravail, s.portfolio, s.github, s.skills, s.specialisation, s.mail, u.video_path, u.image_path, u.cv_path, COUNT(s.id) AS number_of_students FROM students s
				-- LEFT JOIN profiles p ON s.id = p.student_id
				LEFT JOIN availability a ON s.disponibility_id = a.id
				LEFT JOIN uploads u ON s.id = u.student_id
				LEFT JOIN departments d ON s.department_id = d.dep_id
				GROUP BY s.nom, s.prenom ORDER BY s.disponibility_id";
				$stmt = $pdo->query($query);
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) > 0) {
					foreach ($result as $display) {
						$profile_string = $display['specialisation'];
						$skills_string = $display['skills'];
						$profile_array = explode(",", $profile_string);
						$skills_array = explode(",", $skills_string);
				?>
						<div class="col-12 col-md-3 student-card">
							<div class="card card_width mb-3" id="borders">
								<div class="card-body">
									<div>
										<!-- $$$$$$$$$$$******************###########################******************$$$$$$$$$$$$$$$$$$$**************** -->
										<img alt="Profile Picture" width="140" height="140" class="styleImg" <?= ($display["image_path"] == NULL) ? 'id="uploadedImage" src="https://via.placeholder.com/300x300?text=Profile+Picture"' : 'src="' . "ibukun/". $display["image_path"] . '" alt="Uploaded Image"' ?>>

										<!-- <h3 class="space"><?= $display['prenom'] ?> <br> <?= $display['nom'] ?></h3> -->
										<h3 class="space"><?= $display['prenom'] ?></h3>
									</div><br>
									<p class="inline-buttons">
										<?php
										foreach ($profile_array as $profile_id) {
											// Query the value of the profile from the 'specialisation' table
											$profile_query = "SELECT specialisation FROM specialisation WHERE id = ?";
											$profile_stmt = $pdo->prepare($profile_query);
											$profile_stmt->execute([$profile_id]);
											$profile_result = $profile_stmt->fetch(PDO::FETCH_ASSOC);
											if ($profile_result) {
										?>
												<span class="btn small-btn profileTags"><?= $profile_result['specialisation'] ?></span>
										<?php
											}
										}
										?>
									</p>
									<p id="skill_number" class="skillTags">⚙️ 
										<?php
										foreach ($skills_array as $skill_id) {
											// Query the value of the skill from the 'skills' table
											$skill_query = "SELECT skill FROM skills WHERE id = ?";
											$skill_stmt = $pdo->prepare($skill_query);
											$skill_stmt->execute([$skill_id]);
											$skill_result = $skill_stmt->fetch(PDO::FETCH_ASSOC);
											if ($skill_result) {
										?>
												<?= $skill_result['skill'] ?>,
										<?php
											}
										}
										?>
									</p>
									<p>📍 Disponible pour travail á <span id="bold_text" class="locationTag"><?= $display['location'] ?>, <?= $display['department'] ?><?= ($display['anywhere'] == 1) ? ", Tout la France(présentiel)" : "" ?><?= ($display['europe'] == 1) ? ", Europe(présentiel)" : "" ?>.</span></p>
									<p>Prète pour travail <span id="bold_text" class="availabilityTag"><?= $display['disponibility'] ?>.</span></p>
									<p>Modèle de travail: <span id="bold_text" class="modeleTag"><?= ($display['teletravail'] == 1) ? "Teletravail, Hybrid" : "Présentiel, Hybrid" ?>.</span></p>
									<p id="bold_text"> 📩 <?= $display['mail'] ?></p>
									<p id="hidden"><span class="departmentTag"><?= $display['dep_id']; ?></span></p>
									<p id="hidden"><span class="contratsTag"><?= $display['contrats'] ?></span></p>
									<p id="hidden"><span class="europeTag"><?= $display['europe'] ?></span></p>
									<!-- video button ?-->
									<span>
									<?php if ($display['video_path'] !== NULL): ?>
										<button type="button" id="btns" onclick="viewUpload('<?= $display['id']; ?>', 'user-video')" class="viewPromoBtn btn btn-primary btn-sm">Vidéo 📹</button>
									<?php endif; ?>
									</span>
									<!-- portfolio button ?-->
									<span>
									<?php if ($display['portfolio'] !== NULL): ?>
										<button type="button" id="btns" value="<?= $display['id']; ?>" onclick="location.href='<?= $display['portfolio']; ?>'" class="editPromoBtn btn btn-primary btn-sm">Portfolio 🗒</button><br />
									<?php endif; ?>
									</span>
									<div>
									<button type="button" id="btns" value="<?= $display['id']; ?>" class="btn btn-info btn-sm ">Recommendation 🖊️</button>
									<!-- github link ?-->
									<span>
									<?php if ($display['github'] !== NULL): ?>
										<button type="button" id="btns" value="<?= $display['id']; ?>" onclick="location.href='<?= $display['github']; ?>'" class="deletePromoBtn btn btn-info btn-sm">Git 📁</button>
									<?php endif; ?>
									</span>
									<!-- CV ?-->
									<span>
									<?php if ($display['cv_path'] !== NULL): ?>
										<button type="button" id="btns" onclick="viewUpload('<?= $display['id']; ?>', 'cvitae')" class="deletePromoBtn btn btn-info btn-sm ">Voir CV  🧾</button>
									<?php endif; ?>
									</span>
									</div>
								</div>
								<div class="container-fluid">
									<div class="footer-content text-center small">
										<p>Creé <span class="intra" onclick="location.href='route123/reg.php'">votre Template</span></p>
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