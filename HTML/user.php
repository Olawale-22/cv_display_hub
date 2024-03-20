<?php
require_once("../PHP/config.php");

if(!isset($_SESSION['connected'])){
        if(!$_SESSION['connected']){
                header("Location: ../index.php");
        }
        header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CSM Punctuality</title>
        <link rel="stylesheet" media="screen and (min-width: 821px)" href="../CSS/user.css">
        <link rel="stylesheet" media="screen and (max-width: 820px)" href="../CSS/user_phone.css" type="text/css">
    <!-- School Icon -->
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <!-- <script src="../JS/horloge.js"></script> -->
    <link rel="stylesheet" href="../public/css/index.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
         <!-- Include the CSS file for alertify.js -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
</head>
        <script src="../JS/user.js"></script>
<body>
        <!-- Include the alertify.js library -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
        <script>
                let ps = "<?php echo $_SESSION['pseudo']; ?>";
                        console.log("ps: ", ps);
                        let Uid = "<?php echo $_SESSION['id']; ?>";
                        console.log("Uid: ", Uid);

                        $(document).ready(function () {
                        // Assuming you have correctly passed the Uid value from PHP to JavaScript

                        fetch('../websockets/endedsessionuser.php?profId=' + encodeURIComponent(Uid))
                        .then(response => {
                                if (response.ok) {
                                return response.text();
                                } else {
                                throw new Error('Network response was not ok.');
                                }
                        })
                        .then(result => {
                                if (result.status == 200) {
                                alertify.set('notifier', 'position', 'top-right');
                                alertify.success(result.message);
                                } else {
                                alertify.set('notifier', 'position', 'top-right');
                                alertify.success(result.message);
                                }
                        })
                        .catch(error => {
                                console.error('Error:', error);
                        });
                        
                // create a WebSocket connection to the server
                // var socket = new WebSocket("ws://10.10.0.21:8282/websockets/websocket_server.php");
                var socket = new WebSocket("ws://localhost:8282/emergement_csm/websockets/websocket_server.php");

                socket.onopen = function(event) {
                        const msssg = "heartbeat " + Uid;
                        var heartMessage = {
                                id: Uid,
                                message: msssg
                        };

                        setInterval(function() {
                                socket.send(JSON.stringify(heartMessage));
                        }, 2500);
                };
                        let setId;

                        socket.onmessage = function(event) {
        console.log("event: ", event.data);
   try {
                        const data = JSON.parse(event.data); // Parse the JSON string into an object

                        if (data.message.substr(0, 7) === "subject") {
                                const subjectName = data.message.substr(8);
                                const setId = data.id;
                                console.log("setId: ", setId);
                                console.log("subjectName: ", subjectName);

                                // Make an asynchronous request to the server to verify user subsription
                                fetch('../websockets/verify_wssub.php?subject=' + encodeURIComponent(subjectName + " " + Uid))
                                        .then(response => {
                                                if (response.ok) {
                                                return response.json(); // Parse the response as JSON
                                                } else {
                                                        throw new Error('Network response was not ok.');
                                                }
                                        })
                                        .then(result => {
                                                const isSubscribed = result.subscribed; // Assuming the server returns { subscribed: true/false }
                                                console.log("subscribe: " + isSubscribed);
                                                const buttonElement = document.createElement("button");
                                                buttonElement.setAttribute("id", data.sId);

                                                buttonElement.style.color = "white";
                                                buttonElement.style.padding = "10px";
                                                buttonElement.style.marginTop = "8%";
                                                buttonElement.style.marginLeft = "16%";
                                                buttonElement.style.marginBottom = "-2%";
                                                buttonElement.style.border = "none";
                                                buttonElement.style.fontSize = "0.8rem";
                                                buttonElement.style.display = "flex";
                                                buttonElement.style.zIndex = '2';
                                                buttonElement.style.flexDirection = "column";
                                                buttonElement.style.display = 'block';
                                                buttonElement.style.textTransform = "uppercase";

                                                if (isSubscribed === true) {
                                                        buttonElement.innerHTML = "You have subscribed to " + subjectName;
                                                        buttonElement.style.backgroundImage = 'linear-gradient(90deg, #951e81, #e41019 100%, #96c225)';
                                                        buttonElement.disabled = true;
                                                } else {
                                                        buttonElement.style.backgroundColor = 'hsl(216, 96%, 52%)';
                                                        buttonElement.innerHTML = subjectName + " in session";
                                                        buttonElement.addEventListener("click", function() {
                                                                const subscriptionMessage = "subscribe " + Uid;
                                                                console.log("subscriptionMessage: ", subscriptionMessage);
                                                                var SubconnectionMessage = {
                                                                        pseudo: ps,
                                                                        id: setId,
                                                                        subName: subjectName,
                                                                        sId: data.sId,
                                                                        message: subscriptionMessage
                                                                };

                                                                socket.send(JSON.stringify(SubconnectionMessage));

                                                                buttonElement.innerHTML = "You have subscribed to " + subjectName;
                                                                buttonElement.style.backgroundImage = 'linear-gradient(90deg, #951e81, #e41019 100%, #96c225)';
                                                                buttonElement.disabled = true;
                                                        });
                                                }
                                                const containerElement = document.getElementById("button-container");
                                                containerElement.appendChild(buttonElement);
                                        })
                                        .catch(error => {
                                                console.error('Error:', error);
                                        });
                        } else if (data.message.substr(0, 9) === "heartbeat") {
                                // Rest of the code remains the same
                                if (Uid === data.id) {
                                                const containerElement = document.getElementById("heartbeat-container");
                                                var backgroundDiv = document.getElementById("backgroundDiv");

                                                if (!backgroundDiv) {
                                                backgroundDiv = document.createElement('div');
                                                backgroundDiv.textContent = 'je suis la';
                                                backgroundDiv.style.color = 'white';
                                                backgroundDiv.style.position = 'absolute'
                                                //backgroundDiv.style.fontSize = '.5rem';
                                                backgroundDiv.style.display = 'flex';
                                                backgroundDiv.style.justifyContent = 'center';
                                                backgroundDiv.style.alignItems = 'center';
                                                backgroundDiv.setAttribute('id', 'backgroundDiv');
                                                containerElement.appendChild(backgroundDiv);
                                                }
                                                // Start blinking animation
                                        backgroundDiv.classList.add('blink');
                                        }
                                        else {
                                                var backgroundDiv = document.getElementById("backgroundDiv");
                                                if (backgroundDiv) {
                                                backgroundDiv.remove();
                                                }
                                        }
                        }
        } catch (error) {
        // Handle the error when event.data is not valid JSON
        console.log("non json onmessage to user ", event.data);
        }

                };

                socket.onerror = function(event) {
                console.log("WebSocket error: " + event);
                };

                socket.onclose = function(event) {
                console.log("WebSocket connection closed: " + event);
                
                };
                        });
        </script>

<!-- </head> -->
<!-- <body onload="showDate();displayHoursByWeek();"> -->
<!-- <body> -->
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
        <!-- <div class="imgContainer"> -->
        <div class="styleImg">
                <img id="uploadedImage" class="styleImg" src="https://via.placeholder.com/300x300?text=Profile+Picture" alt="Profile Picture" width="180" height="180">
                <div class="uploadIcon" onclick="openFileUploader()">
                📷 <!-- Unicode pen icon -->
                </div>
                <canvas id="myCanvas" width="10" height="10"></canvas>
        </div>
        <!-- </div> -->

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
                const formData = new FormData();
                formData.append('imageData', imageData);
                formData.append('UserId', "<?php echo $_SESSION['id']; ?>");

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
        </header>

        <div class="trows" id="margeens">
                <h3 class="sidebar-heading"><u>LIVE SESSION</u></h3>
                <div id="heartbeat-container"></div>
                <div id="button-container" class="padd"></div>
        </div>

        <div class="trows">
                <h3 class="sidebar-heading"><u>LOGS</u></h3>
                <table id="logs" class="bbg container">
                        <thead>
                        <tr>
                                <th>Date</th>
                                <th>Cours</th>
                                <th>Enter Time</th>
                        </tr>
                        </thead>
                        <!-- *********** -->
                <tbody id="tbody">
                        <?php

                        $pdo = getPDO();
                        $name = $_SESSION['pseudo'];
                        $query = "SELECT w.id, w.currentDate, w.subject_name, w.enterTime FROM wlogs w LEFT JOIN students s ON w.student_id = s.id WHERE s.pseudo = :name";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->execute();
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($results) > 0) {
                                foreach ($results as $sujet) {
                        ?>
                        <tr>
                                <td><?= $sujet['currentDate'] ?></td>
                                <!-- <td><?= $sujet['pseudo'] ?></td> -->
                                <td><?= $sujet['subject_name'] ?></td>
                                <td><?= date('H:i:s', strtotime($sujet['enterTime'])) ?></td>
                        </tr>
                        <?php
                                }
                        }
                        ?>
                </tbody>
                </table>
        </div>
</body>
</html>
