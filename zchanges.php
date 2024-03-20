<!DOCTYPE html>
<html>
<head>
<style>
    /* ... Your styles here ... */
</style>
</head>
<body>
<div class="imgContainer">
    <div class="styleImg">
        <img id="uploadedImage" src="https://via.placeholder.com/300x300?text=Profile+Picture" alt="Profile Picture">
        <div class="uploadIcon" onclick="openFileUploader()">
            🖊 <!-- Unicode pen icon -->
        </div>
        <canvas id="myCanvas" width="10" height="10"></canvas>
    </div>
</div>

<script>
    function openFileUploader() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.addEventListener('change', handleFileSelect);
        input.click();
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            const uploadedImage = document.getElementById('uploadedImage');
            uploadedImage.src = e.target.result;
            uploadedImage.style.display = 'block'; // Show the uploaded image

            // Call a function to save the image on the server
            saveImageToServer(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    function saveImageToServer(imageData) {
    // Create a FormData object to send the image data as a file
        const formData = new FormData();
        formData.append('imageData', imageData);

        // Make an AJAX request using the Fetch API
        fetch('user_action.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Image saved successfully.');
                // You can display a success message to the user or perform other actions
            } else {
                console.error('Error saving image.');
                // Handle the error case, display an error message, etc.
            }
        })
        .catch(error => {
            console.error('AJAX request error:', error);
            // Handle the error case, display an error message, etc.
        });
    }

</script>

<!-- $$$$$$$$$$$****************$$$$$$$$$$$$$$$$$$$$ -->

<header>
        <div class="navbar-brand-wrapper d-flex align-items-center col-auto">
            <!-- Logo For Mobile View -->
            <a class="navbar-brand navbar-brand-mobile" href="/">
                <img class="img-fluid w-100" src="../image/logo_csm.png" style="width: auto; height: 20px;" alt="Graindashboard">
            </a>
            <!-- End Logo For Mobile View -->

            <!-- Logo For Desktop View -->
            <a class="navbar-brand navbar-brand-desktop" href="/">
                <img class="side-nav-show-on-closed" src="../image/logo_csm.png" alt="Graindashboard" style="width: auto; height: 33px;">
                <img class="side-nav-hide-on-closed" src="../image/logo_csm.png" alt="Graindashboard" style="width: auto; height: 33px;">
            </a>
            <!-- End Logo For Desktop View -->
        </div>
        <!-- Profile Image -->
        <div class="styleImg">
        <!-- <img id="uploadedImage" class="styleImg" alt="Uploaded Image"> -->
        <img id="uploadedImage" class="styleImg" src="https://via.placeholder.com/300x300?text=Profile+Picture" alt="Profile Picture" width="180" height="180">
        <div class="uploadIcon" onclick="openFileUploader()">
        📷
        <!-- Add your upload icon here -->
        </div>
        <canvas id="myCanvas" width="10" height="10"></canvas>
        </div>
        
        <!--/ imageee -->

                <div class="bienvenue blueText">
                        Bienvenue <?php  echo($_SESSION['pseudo']); ?>
                </div>
                <img></img>
                <!-- <div class="margeens"> -->
                <span class="side-heading h6">
                    <!-- <i>focus on positivity...</i> -->
                    <i>Rouen📍</i>
                </span>
                <span class="floatright">
                <div class="deco_div goldenTxt">
                    <p class="deco gdTxt" onclick="location.href='https://planning-campus-saint-marc.hyperplanning.fr/hp/'">Espace Planning</p>
                    <p>|</p>
                    <p class="deco gdTxt" onclick="location.href='../PHP/deco.php'">Se deconnecter</p>

                </div>
                </span>
                <!-- <div> -->
        <script>
        function openFileUploader() {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.addEventListener('change', handleFileSelect);
                input.click();
        }

        // function sendtoDatabase

        function handleFileSelect(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                const uploadedImage = document.getElementById('uploadedImage');
                uploadedImage.src = e.target.result;
                uploadedImage.style.display = 'block'; // Show the uploaded image
                };
                reader.readAsDataURL(file);
        }

        </script>
        </header>


        <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
        <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
        <h4 class="title">Connexion</h4></br>
								<br>
								<form action="PHP/sign.php" method="get">
									<div class="form-group">
										<label for="email" >Username</label>
										<!-- <input id="email" type="email" class="form-control" name="email" required="" autofocus=""> -->
                                        <li><input type="text" name="pseudo" class="fill_input" required></li>
									</div>

									<div class="form-group">
										<label for="password">Password
										</label>
										<!-- <input id="password" type="password" class="form-control" name="password" required=""> -->
                                        <li><input type="password" name="password" class="fill_input" required></li>
										<div class="text-right">
											<a href='./HTML/pchange.php' class="small">
												Réinitialisation du mot de passe?
											</a>
										</div>
									</div>

									<div class="form-group">
                                        <?php
                                            if(isset($_SESSION['error'])){
                                                echo "<p class=\"p_error\">".$_SESSION['error']."</p>";
                                                unset($_SESSION['error']);
                                            }
                                        ?>
									</div>

									<div class="form-group no-margin">
										<!-- <a href="/index.html" class="btn btn-primary btn-block">
											Sign In
										</a> -->
                                        <input type="submit" value="On y va" name="submit" class="connexion btn-block">
									</div>
									<div class="text-center mt-3 small">
                                        Vous n'avez pas de compte ? <a href='./HTML/sign_up.php'>Sign Up</a>
									</div>
		</form>
        <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
        <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->

</body>
</html>
