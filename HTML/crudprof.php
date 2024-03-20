<?php
require_once("../PHP/config.php");
require_once("../PHP/download.php");

	error_reporting(E_ALL);
        ini_set('display_errors', '1');

if (!isset($_SESSION['connected']) || !isset($_SESSION['admin']) || !$_SESSION['connected'] || !$_SESSION['admin']) {
        header("Location: ../index.php");
        exit();
    }
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Custom CSS -->
    <style type="text/css">

        .tabs_content{
            display: none;
        }

        .tabs_content_active{
            display: block;
        }

        #user-online {
            
            display: contents;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            /* background-color: green; */
            margin-left: 2%;
            z-index: 999;
        }

        .subs-list{
            display: contents;
            border-color: green;
            z-index: 999;
        }

    </style>
    <!-- School Icon -->
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">

    <title>CSM - VIP</title>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../CSS/crud.css">

</head>
<body>

<!-- Add Student -->
<div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title txt" id="exampleModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveStudent">
                <div class="modal-body">

                    <div id="errorMessageAddStudent" class="alert alert-warning d-none"></div>
                    
                    <div class="mb-3">
                        <label for="" class="txt">Student</label>
                        <br>
                            <select id="logSelectorStudent" class="selectorStudentModal" name="student_id">
                                <?php getStudents(); ?>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="txt">Cours</label>
                        <br>
                        <select id="edit_promotion_id" class="selectorPromotionStudent" name="subject_id" style="width: 100%;">
                            <?php getSujets(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStudent">
                <div class="modal-body">

                    <div id="errorMessageUpdateStudent" class="alert alert-warning d-none"></div>

                    <input type="hidden" name="student_id" id="student_id" >

                    <div class="mb-3">
                        <label for="">Pseudo</label>
                        <input type="text" name="pseudo" id="pseudo" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="">Prénom</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="">Nom</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" />
                    </div>
                        <label for="">Promotion</label>
                        <br>
                        <select id="edit_promotion_id" class="selectorPromotionStudent" name="promo_id" style="width: 100%">
                            <?php getSujets(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Update Student ✅</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Log Modal-->
<div class="modal fade" id="logAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveLog">
                <div class="modal-body">
                    <div id="errorMessageAddLog" class="alert alert-warning d-none"></div>
                    <div class="mb-3">
                        <label for="">Student</label>
                        <select id="logSelectorStudent" class="selectorStudentModal" name="studentId">
                            <?php getStudents(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Cours</label>
                        <select id="edit_promotion_id" class="selectorPromotionStudent" name="promo_id" style="width: 100%">
                            <?php getSujets(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="currentDate">Date</label>
                        <input type="date" id="currentDate" name="currentDate" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label for="enterTime">Enter Time</label>
                        <input type="time" step="1" name="enterTime" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Create Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Sujet -->
<div class="modal fade" id="promoAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title txt" id="exampleModalLabel">Add Sujet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveSujet">
                <div class="modal-body">
                    <div id="errorMessageAddPromo" class="alert alert-warning d-none"></div>
                    <div class="mb-3">
                        <label for="" class="txt">Intitulé Sujet</label>
                        <input type="text" name="nom_sujet" class="form-control" />
                        <input name="session_id" value="<?php echo $_SESSION['id']; ?>" hidden />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton" style="background: #0157b2; border-color: #0157b2;">Save Sujet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Promo Modal -->
<div class="modal fade" id="promoEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateSujet">
                <div class="modal-body">

                    <div id="errorMessageUpdateStudent" class="alert alert-warning d-none"></div>

                    <input type="hidden" name="promo_id" id="promo_id" >

                    <div class="mb-3">
                        <label for="">Intitulé Promo</label>
                        <input type="text" name="nom_sujet" id="nom_sujet" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Update Sujet ✅</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pick and Start Promo Session -->
<div class="modal fade" id="subjectSession" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <!-- <form id="start-session-form" method="POST" action="../PHP/enter.php" onsubmit="sendMessage(); return false;"> -->
            <form id="start-session-form" method="POST" action="../PHP/enter.php">
            <div class="modal-header">
                <h5 class="modal-title txt" id="exampleModalLabel">Sélect Cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <br>
                        <select id="start_promotion_id" class="selectorPromotionStudent" name="subject_id" style="width: 100%;">
                            <?php getSujets(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                <button class="btn btn-primary" type="submit" id="start-session-button">Start Session</button>
                </div>
            </form>
        </div>
        </script>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card tabs">
                <div class="card-header tabs_sidebar">
                <button class="tabs_button tabs_button_active" data-for-tab="4">Live</button><button class="tabs_button" data-for-tab="1">Students</button><button class="tabs_button" data-for-tab="2">Logs</button><button class="tabs_button" data-for-tab="3">Sujets</button>
                    <button class="btn btn-danger float-end merge" onclick="location.href='../PHP/deco.php'">Deconnexion</button>
                    <button class="btn btn-primary float-end merge" id="download-button">Download Data</button>
                    <button class="btn btn-primary float-end merge" onclick="location.href = '../PHP/exit.php'" id="end-session-button">End Session</button>
                    <!-- trigger Subject event session -->
                    <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#subjectSession">Start Session</button>
                    <!-- <div class="float-end" id="heart-container"></div> -->
                    <?php
                    if(isset($_SESSION['error'])){
                        echo "<p class=\"p_error\">".$_SESSION['error']."</p>";
                        unset($_SESSION['error']);
                    }
                    ?>
                </div>
                <div class="card-body tabs_content" data-tab="1">
                    <div class="card-filter">
                        <select id="selectorPromo" class="selectorPromoView" name="promoId">
                            <option value="">Tous vos sujets</option>
                            <?php getSujets(); ?>
                        </select>
                        <input type="text" id="searchBox" onkeyup="search()" placeholder="Search...">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                            Add Student
                        </button>
                    </div>
                    <table id="studentTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Pseudo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Cours</th>
                            <th>Appearance in the Month</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
<?php
try {
    $query = "
        SELECT s.student_id AS studentId, s.pseudo, s.prenom, s.nom, s.subject_id AS subjectId, su.nom_sujet, COUNT(wl.student_id) AS numAP
        FROM subjects s
        LEFT JOIN sujet su ON s.subject_id = su.id
        LEFT JOIN wlogs wl ON s.subject_id = wl.subject_id AND s.student_id = wl.student_id
        WHERE su.prof_id = ?
        GROUP BY s.student_id, s.pseudo, s.prenom, s.nom, s.subject_id, su.nom_sujet
        ORDER BY s.nom DESC, s.prenom DESC
    ";

    $pdo = getPDO();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['id']]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($students) > 0) {
        foreach ($students as $student) {
            ?>
            <tr>
                <td><?= $student['pseudo'] ?></td>
                <td><?= $student['nom'] ?></td>
                <td><?= $student['prenom'] ?></td>
                <td><?= $student['nom_sujet'] ?></td>
                <td><?= $student['numAP'] ?></td>
                <!-- <td><?= $student['mail'] ?></td> -->
                <td>
                    <button type="button" value="<?= $student['studentId'] ?>" class="viewLogsBtn btn btn-primary btn-sm">Logs 📄</button>
                    <!-- <button type="button" value="<?= $student['studentId'] ?>" class="editStudentBtn btn btn-success btn-sm">Edit 🖊️</button> -->
                    <button type="button" value="<?= $student['studentId'] . ' ' . $student['subjectId'] ?>" class="deleteStudentBtn btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="6">Aucun élève trouvé.</td></tr>';
    }
} catch (PDOException $e) {
    echo '<tr><td colspan="6">An error occurred while fetching students.</td></tr>';
    // Log or handle the error as needed
}
?>
</tbody>

                    </table>
                </div>

                <?php

                function getStudents(){
                    $pdo = getPDO();
                    $q = $pdo->prepare("SELECT id, nom, prenom, pseudo from students WHERE admin = false ORDER BY nom, prenom");
                    $q->execute();
                    while($res = $q->fetch()){
                        echo("<option value=". $res['id'] . ">". $res['nom']. " "  . $res['prenom']. " (". $res['pseudo'].")</option>");
                    }
                }

                function getSujets(){
                    $pdo = getPDO();
                    $q = $pdo->prepare("SELECT id, nom_sujet FROM sujet WHERE prof_id = :prof_id ORDER BY id");
                    $q->bindParam(':prof_id', $_SESSION['id']);
                    $q->execute();
                    while($res = $q->fetch()){
                        echo("<option value=". $res['id'] . ">". $res['nom_sujet']. "</option>");
                    }
                }

                ?>

                <div class="card-body tabs_content" data-tab="2">
                    <div class="card-filter">
                        <select id="selectoPromo" class="selectorPromoView" name="promoId">
                            <option value="">Tous vos sujets</option>
                            <?php getSujets(); ?>
                        </select>
                        <select id="selectorStudent" class="selectorStudentView" name="studentId">
			<option value="">Search Student</option>
                            <?php getStudents(); ?>
                        </select>
                        <select name="logsMonth" id="selectorMonth">
                            <option value="00">Mois</option>
                            <option value="01">Janvier</option>
                            <option value="02">Février</option>
                            <option value="03">Mars</option>
                            <option value="04">Avril</option>
                            <option value="05">Mai</option>
                            <option value="06">Juin</option>
                            <option value="07">Juillet</option>
                            <option value="08">Aout</option>
                            <option value="09">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Décembre</option>
                        </select>
                        <select id="selectorYear">
                            <option value="00">Année</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#logAddModal">
                            Add Logs
                        </button>
                    </div>

                    <table id="logTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Pseudo</th>
                                <th>Subject</th>
                                <th>Enter Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="tbodyLogs">
                            <!-- Table rows will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
                <div class="card-body tabs_content tabs_content_active" data-tab="4">
                    <h2>SESSIONS EN DIRECT ICI...</h2><div id="heart-container"></div><br>
                    <div id="subscribers-list" class="subs-list">
                    </div>
                </div>
                <div class="card-body tabs_content" data-tab="3">
                    <div class="card-filter">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#promoAddModal">
                            Add Sujet
                        </button>
                    </div>
                    <table id="promoTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la sujet</th>
                            <th>Nombre d'élèves</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
    <?php
	$pdo = getPDO();
    $query = "SELECT p.id, p.nom_sujet, COUNT(s.id) AS number_of_students 
              FROM sujet p 
              LEFT JOIN subjects s ON p.id = s.subject_id 
              WHERE p.prof_id = ? 
              GROUP BY p.id, p.nom_sujet";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['id']]);
    $sujets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($sujets) > 0) {
        foreach ($sujets as $sujet) {
            ?>
            <tr>
                <td><?= $sujet['id'] ?></td>
                <td><?= $sujet['nom_sujet'] ?></td>
                <td><?= $sujet['number_of_students'] ?></td>
                <td>
                    <button type="button" value="<?= $sujet['id'] ?>" class="viewStudentBtn btn btn-primary btn-sm">Students 👨💻</button>
                    <button type="button" value="<?= $sujet['id'] ?>" class="editPromoBtn btn btn-success btn-sm">Edit 🖊️</button>
                    <button type="button" value="<?= $sujet['id'] ?>" class="deletePromoBtn btn btn-danger btn-sm">Delete ❌</button>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
	var preNom = "<?php echo $_SESSION['prenom']; ?>";
	var SuID = localStorage.getItem('subject_id');
		console.log("SuID-from-local-storage: ", SuID);
    $(document).ready(function(){
	fetch('../websockets/connusers_prof.php?profId=' + encodeURIComponent(Uid + " " + SuID))
        .then(response => {
            if (response.ok) {
                return response.json(); // Parse the response as plain text
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .then(result => {
            if (result.status == 500 || result.status == 220) {
                alertify.set('notifier','position', 'top-right');
                alertify.success(result.message);
                
            } else if (result.status == 200) {
                var active_users = result.data;

                    const buttonElement = document.createElement("button");
                  
                    buttonElement.style.backgroundColor = 'hsl(216, 96%, 52%)';
                    buttonElement.style.color = "white";
                    buttonElement.style.padding = "10px";
                    buttonElement.style.marginTop = "1%";
                    buttonElement.style.border = "none";
                    buttonElement.style.fontSize = "0.8rem";
                    buttonElement.style.display = "flex";
                    buttonElement.style.flexDirection = "column";
                    buttonElement.style.textTransform = "uppercase";
		    
		    if (active_users !== undefined) {
                        buttonElement.innerHTML = active_users;
                    } else {
                        buttonElement.innerHTML = 'une session terminée';
                    }
                    
                    const containerElement = document.getElementById("subscribers-list");
                    containerElement.appendChild(buttonElement);
               
	} else if (result.status == 422){
		var output = result.message + " " + preNom + " 😎";
                alertify.set('notifier', 'position', 'top-right');
                alertify.success(output);
        } else {
                alertify.set('notifier', 'position', 'top-right');
                alertify.success(result.message);
        }
        })
        .catch(error => {
            console.error('Error:', error);
        });

        let selectorMonth = document.getElementById("selectorMonth");
        let modalCurrentDate = document.getElementById("currentDate");
        const d = new Date();
        
        let year = d.getFullYear(); // get the year
        let month = ("0" + (d.getMonth() + 1)).slice(-2); // get the month and pad it with a leading zero if necessary
        let day = ("0" + d.getDate()).slice(-2); // get the day and pad it with a leading zero if necessary

        let formattedDate = year + "-" + month + "-" + day; // concatenate the year, month, and day with dashes

        selectorMonth.selectedIndex = d.getMonth() + 1;
        selectorYear.selectedIndex = d.getFullYear() - 2021; // To get exact
        modalCurrentDate.value = formattedDate;

        //Setting up select2
        $('.selectorStudentModal').select2({
            dropdownParent: $("#logAddModal")
        });

        $('.selectorStudentView').select2();

        // --- Handling Download ---
        $("#download-button").click(function() {
            var promo_id = $('#selectorPromo').val();
            //console.log(promo_id);
            $.ajax({
                url: "../PHP/download.php",
                type: "POST",
                data: { action: "download", promotion: promo_id },
                success: function(csv) {
                    var date = new Date().toISOString().slice(0, 10);
                    var blob = new Blob([csv], { type: "text/csv" });
                    var link = document.createElement("a");
                    var promotionName = document.querySelector('#selectorPromo');
                    promotionName = promotionName.options[promotionName.selectedIndex].text;

                    link.href = window.URL.createObjectURL(blob);
                    link.download = "data-" + date + "-" + promotionName + ".csv";
                    link.click();
                }
            });
        });
        // !-- Handling Download --!

        // Show Logs Once
        updateFilteredData();
        // Then auto refresh logs
        setInterval(updateFilteredData, 5000);
    });

    var ps = "<?php echo $_SESSION['pseudo']; ?>";
       console.log("ps: ", ps);
        // user_id
        var Uid = "<?php echo $_SESSION['id']; ?>";
        console.log("Uid: ", Uid);

    //******************* */
    // filter logs here
    function updateFilteredData() {
        var promoId = document.getElementById("selectoPromo").value;
        var studentId = document.getElementById("selectorStudent").value;
        var logsMonth = document.getElementById("selectorMonth").value;
        var selectorYear = document.getElementById("selectorYear").value;

        // Send an AJAX request to fetch filtered data
        fetch("crud_actionprof.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "promoId=" + promoId + "&studentId=" + studentId + "&logsMonth=" + logsMonth + "&selectorYear=" + selectorYear + "&Uid=" + Uid
        })
        .then(function(response) {
	// console.log("responseFilteredDate: ", response);
            if (response.ok) {
                return response.text();
            } else {
                throw new Error("Error: " + response.status);
            }
        })
        .then(function(data) {
	// console.log("dataFilteredData: ", data);
        // Update the table body with the fetched data
            var jsonData = JSON.parse(data);

            // Update the table body with the fetched data
            displayRows(jsonData);
        })
        .catch(function(error) {
            console.error(error);
        });
    }

    // Add event listeners to the logs filter elements
    document.getElementById("selectoPromo").addEventListener("change", updateFilteredData);
    document.getElementById("selectorStudent").addEventListener("change", updateFilteredData);
    document.getElementById("selectorMonth").addEventListener("change", updateFilteredData);
    document.getElementById("selectorYear").addEventListener("change", updateFilteredData);

    // Initial fetch
    updateFilteredData();

    //****************** */
    //var socket = new WebSocket("ws://10.10.0.21:8282/websockets/websocket_server.php");
    var socket = new WebSocket("ws://localhost:8282/emergement_csm/websockets/websocket_server.php");

    socket.onopen = function(event) {
        console.log('WebSocket connection established');

        const msssg = "profheartbeat " + Uid;
			var heartMessage = {
				id: Uid,
				message: msssg
		};

        setInterval(function() {
            socket.send(JSON.stringify(heartMessage));
        }, 2500);
    };
    
    let msg;

    document.getElementById('start-session-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var subjectId = document.getElementById("start_promotion_id").value;
        var message = "connection " + subjectId;
	localStorage.setItem('subject_id', subjectId); // Store subject_id in local storage
        console.log(message);
        var connectionMessage = {
            pseudo: ps,
            id: Uid, 
            message: message
        };

        console.log("connectionMessage: ", connectionMessage);
        socket.send(JSON.stringify(connectionMessage));
        console.log("json sent: ", JSON.stringify(connectionMessage));
        document.getElementById('start-session-form').submit();
    });

    document.getElementById('end-session-button').addEventListener('click', function(e) {
        e.preventDefault();
	localStorage.removeItem('subject_id'); // Remove subject_id from local storage
        socket.send("endsession");
    });

    socket.onmessage = function(event) {
	try {
                const data = JSON.parse(event.data); // Parse the JSON string into an object

                if (data.message.substr(0, 11) === "subscribers") {
                    const userIID = data.message.substr(12);
                    // admin id
                    const addId = data.id;
                    if (data.id === Uid) {
                        const buttonElement = document.createElement("button");
                        buttonElement.setAttribute("id", userIID);
                        buttonElement.style.backgroundColor = 'hsl(216, 96%, 52%)';
                        buttonElement.style.color = "white";
                        buttonElement.style.padding = "10px";
                        buttonElement.style.marginTop = "1%";
                        buttonElement.style.border = "none";
                        buttonElement.style.fontSize = "0.8rem";
                        buttonElement.style.display = "flex";
                        buttonElement.style.flexDirection = "column";
                        buttonElement.style.textTransform = "uppercase";
                        buttonElement.innerHTML = data.pseudo;

                        const containerElement = document.getElementById("subscribers-list");
                        containerElement.appendChild(buttonElement);
                    }
                } else if (data.message.substr(0, 13) === "profheartbeat") {
					const subjectHyd = data.message.substr(8);
					if (Uid === data.id) {
						const containerElement = document.getElementById("heart-container");
						var backgroundDiv = document.getElementById("backgroundDiv");

						if (!backgroundDiv) {
						backgroundDiv = document.createElement('div');
						backgroundDiv.textContent = 'je suis la';
						backgroundDiv.style.color = 'white';
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
                console.log(event.data);
            }
    };
    //****************** */

    socket.onerror = function(error) {
        console.log('WebSocket Error ' + error);
    };

    socket.onclose = function(error) {
        console.log('WebSocket connection closed: ' + console);
    };
    //********************************* */

    //Adding Student
    $(document).on('submit', '#saveStudent', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_studentSub", true);

        $.ajax({
            type: "POST",
            url: "crud_actionprof.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {

                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageAddStudent').removeClass('d-none');
                    $('#errorMessageAddStudent').text(res.message);

                }else if(res.status == 200){
                    $('#errorMessageAddStudent').addClass('d-none');
                    $('#studentAddModal').modal('hide');
                    $('#saveStudent')[0].reset();
                    alertify
                        .alert("Student Created", res.message, function(){
                        });

                    //Refreshing table content after adding
                    $('#studentTable').load(location.href + " #studentTable");
                    $.ajax({
                        url: location.href,
                        success: function(data) {
                            var table = $(data).find('#studentTable tbody');
                            $('#studentTable tbody').replaceWith(table);
                            //filterTable();

                            filterBack();
                        }
                    });

                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });
    });

    // Edit Student (modal)
    $(document).on('click', '.editStudentBtn', function () {
        var student_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "crud_actionprof.php?student_id=" + student_id,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 404) {

                    alert(res.message);
                }else if(res.status == 200){

                    $('#student_id').val(res.data.id);
                    $('#pseudo').val(res.data.pseudo);
                    $('#first_name').val(res.data.prenom);
                    $('#last_name').val(res.data.nom);
                    $('#edit_promotion_id').val(res.data.id_promo);

                    $('#studentEditModal').modal('show');
                }
            }
        });
    });

    // Edit Student (submit)
    $(document).on('submit', '#updateStudent', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("update_student", true);

        $.ajax({
            type: "POST",
            url: "crud_actionprof.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageUpdateStudent').removeClass('d-none');
                    $('#errorMessageUpdateStudent').text(res.message);

                }else if(res.status == 200){
                    $('#errorMessageUpdateStudent').addClass('d-none');

                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);

                    $('#studentEditModal').modal('hide');
                    $('#updateStudent')[0].reset();

                    //Refreshing table content after adding
                    $.ajax({
                        url: location.href,
                        success: function(data) {
                            var table = $(data).find('#studentTable tbody');
                            $('#studentTable tbody').replaceWith(table);

                            var tablePromo = $(data).find('#promoTable tbody');
                            $('#promoTable tbody').replaceWith(tablePromo);

                            filterBack();
                        }
                    });
                    
                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });
    });

    // View Student (redirect to student's logs)
    $(document).on('click', '.viewLogsBtn', function () {
        //We set value of student select to the one we clicked
        var student_pseudo = $(this).val();

        var studentSelect = document.querySelector('#selectorStudent');
        var logStudentSelect = document.querySelector('#logSelectorStudent');
        studentSelect.value = logStudentSelect.value = student_pseudo;

        //Then we go on the logs tab
        var logsTab = document.querySelector('body > div.container.mt-4 > div > div > div > div.card-header.tabs_sidebar > button:nth-child(3)')
        logsTab.click();

        //We refresh content of log tab
        //showLogs();
        updateFilteredData()
    });

    // Delete Student (modal)
    $(document).on('click', '.deleteStudentBtn', function (e) {
        e.preventDefault();

        if(confirm('Voulez-vous vraiment supprimer cette élève?'))
        {
            //var student_id = $(this).val();
            var values = $(this).val().split(' ');
            var student_id = values[0];
            var subject_id = values[1];
            $.ajax({
                type: "POST",
                url: "crud_actionprof.php",
                data: {
                    'delete_student': true,
                    'student_id': student_id,
                    //finish work from here--
                    'subject_id': subject_id
                },
                success: function (res) {
                    //console.log(response);
                    //var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        alert(res.message);
                    }else{
                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);

                        //Refreshing table content after adding
                        //$('#studentTable').load(location.href + " #studentTable");
                        $.ajax({
                            url: location.href,
                            success: function(data) {
                                var table = $(data).find('#studentTable tbody');
                                $('#studentTable tbody').replaceWith(table);

                                //We trigger filters back
                                $('#selectorPromo').trigger('change');
                                $('#searchBox').keyup();

                                filterBack();
                            }
                        });
                    }
                }
            });
        }
    });

    //Workaround to get table back after action
    function filterBack(){
        //Refreshing filter by sujet
        var selectedPromo = $('#selectorPromo option:selected').text();
        $('#studentTable tbody tr').each(function() {
            var rowPromo = $(this).find('td:eq(3)').text(); // Get the sujet name in the 4th column of row
            //console.log(selectedPromo);
            if (selectedPromo === 'Tous vos sujets' || selectedPromo === rowPromo) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    // -------------- Logs -------------

    //Adding Log
    $(document).on('submit', '#saveLog', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_log", true);

        $.ajax({
            type: "POST",
            url: "crud_actionprof.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {

                    $('#errorMessageAddLog').removeClass('d-none');
                    $('#errorMessageAddLog').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageAdd').addClass('d-none');
                    $('#logAddModal').modal('hide');
                    $('#saveLog')[0].reset();

                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);

                    //showLogs();
                    updateFilteredData()
                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });
    // Edit Log (modal)

    // $(document).on('click', '.editLogBtn', function () {
    //     var log_id = $(this).val();

    //     $.ajax({
    //         type: "GET",
    //         url: "crud_actionprof.php?log_id=" + log_id,
    //         success: function (response) {
    //             //console.log(response);
    //             var res = jQuery.parseJSON(response);
    //             if(res.status == 404) {

    //                 alert(res.message);
    //             }else if(res.status == 200){
    //                 $enterDateTime = res.data.enterDate;
    //                 if($enterDateTime !== null){
    //                     $enterDateTime = $enterDateTime.substring(11);
    //                 }

    //                 $exitDateTime = res.data.exitDate;
    //                 if($exitDateTime !== null){
    //                     $exitDateTime = $exitDateTime.substring(11);
    //                 }

    //                 $('#log_id').val(res.data.id);
    //                 $('#date').val(res.data.currentDate);
    //                 $('#enterDate').val($enterDateTime);
    //                 $('#exitDate').val($exitDateTime);

    //                 $('#logEditModal').modal('show');
    //             }

    //         }
    //     });

    // });

    // Edit Log (submit)
    $(document).on('submit', '#updateLog', function (e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append("update_log", true);

        $.ajax({
            type: "POST",
            url: "crud_actionprof.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageUpdateLog').removeClass('d-none');
                    $('#errorMessageUpdateLog').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageUpdateLog').addClass('d-none');

                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);

                    $('#logEditModal').modal('hide');
                    $('#updateLog')[0].reset();

                    //showLogs();
                    updateFilteredData()

                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });

    // Delete Log (modal)
    $(document).on('click', '.deleteLogBtn', function (e) {
        e.preventDefault();

        if(confirm('Are you sure you want to delete this log?'))
        {
            var log_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "crud_actionprof.php",
                data: {
                    'delete_log': true,
                    'log_id': log_id
                },
                success: function (res) {
                    //console.log(response);
                    //var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        alert(res.message);
                    }else{
                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);
                        // reload
                        location.reload();
                        updateFilteredData()
                        //showLogs();
                    }
                }
            });
        }
    });


    // -------------- Promo --------------
    //Adding Promo
    $(document).on('submit', '#saveSujet', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_sujet", true);
        formData.append("nom_sujet", $('input[name="nom_sujet"]').val());
        formData.append("session_id", $('input[name="session_id"]').val());

        $.ajax({
            type: "POST",
            url: "crud_actionprof.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log("add-promo response: ", response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageAddPromo').removeClass('d-none');
                    $('#errorMessageAddPromo').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageAddPromo').addClass('d-none');
                    $('#promoAddModal').modal('hide');
                    $('#saveSujet')[0].reset();

                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);

                    //Refreshing table content after adding
                    $.ajax({
                        url: location.href,
                        success: function(data) {
                            var table = $(data).find('#promoTable tbody');
                            $('#promoTable tbody').replaceWith(table);
                        }
                    });

                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });
    });

    // Edit Promo (modal)
    $(document).on('click', '.editPromoBtn', function () {

        var sujet_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "crud_actionprof.php?promo_id=" + sujet_id,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 404) {
                    alert(res.message);
                }else if(res.status == 200){

                    //console.log(res.data)
                    $('#promo_id').val(res.data.id);
                    $('#nom_sujet').val(res.data.nom_sujet);

                    $('#promoEditModal').modal('show');
                }

            }
        });

    });

    // Edit Promo (submit)
    $(document).on('submit', '#updateSujet', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("update_sujet", true);

        $.ajax({
            type: "POST",
            url: "crud_actionprof.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageUpdatePromo').removeClass('d-none');
                    $('#errorMessageUpdatePromo').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageUpdatePromo').addClass('d-none');

                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);

                    $('#promoEditModal').modal('hide');
                    $('#updateSujet')[0].reset();

                    //Refreshing table content after adding
                    $.ajax({
                        url: location.href,
                        success: function(data) {
                            var table = $(data).find('#promoTable tbody');
                            $('#promoTable tbody').replaceWith(table);
                        }
                    });

                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });
     
    // View Promo (redirect to students page from this sujet)
    $(document).on('click', '.viewStudentBtn', function () {
        //We set value of student select to the one we clicked
        var id_promo = $(this).val();
        console.log("subject_Id del. " + id_promo);

        var promoSelect = document.querySelector('#selectorPromo');
        promoSelect.value = id_promo;

        //Then we go on the logs tab
        var studentTab = document.querySelector('body > div.container.mt-4 > div > div > div > div.card-header.tabs_sidebar > button:nth-child(2)')
        studentTab.click();

        //We refresh content of log tab
        //showLogs();
        updateFilteredData()
        //We also refresh the table according to the filter
        filterBack();
    });

    // Delete Promo (modal)
    $(document).on('click', '.deletePromoBtn', function (e) {
        e.preventDefault();

        if(confirm('Are you sure you want to delete this promotion?'))
        {
            var promo_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "crud_actionprof.php",
                data: {
                    'delete_promo': true,
                    'promo_id': promo_id
                },
                success: function (res) {
                    //console.log(response);
                    //var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        alert(res.message);
                    }else{
                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);

                        //Refreshing table content after adding
                        $.ajax({
                            url: location.href,
                            success: function(data) {
                                var table = $(data).find('#promoTable tbody');
                                $('#promoTable tbody').replaceWith(table);
                            }
                        });
                    }
                }
            });
        }
    });

    // --- Sorting by columns click ---
    // Add a click event listener to the table headers
    $('#studentTable th:not(:last-child):not(:nth-last-child(2))').click(function() {
        // Get the column index and sort direction
        var column = $(this).index();
        var sortDirection = $(this).data('sort-direction');

        // Sort the table rows based on the clicked column's data
        var rows = $('#studentTable tbody tr').get();
        rows.sort(function(a, b) {
            var aVal = $(a).children('td').eq(column).text();
            var bVal = $(b).children('td').eq(column).text();
            if (column == 4) {
                aVal = timeToSeconds(aVal);
                bVal = timeToSeconds(bVal);
            }
            return aVal.localeCompare(bVal, undefined, {numeric: true, sensitivity: 'base'});
        });

        // Update the sort direction and add a visual effect to the clicked header
        $('#studentTable th').removeClass('sort-asc sort-desc');
        if (sortDirection == 'none' || sortDirection == 'desc') {
            rows.reverse();
            $(this).data('sort-direction', 'asc');
            $(this).addClass('sort-asc');
        } else {
            $(this).data('sort-direction', 'desc');
            $(this).addClass('sort-desc');
        }

        // Update the table with the sorted rows
        $('#studentTable tbody').empty();
        $.each(rows, function(index, row) {
            $('#studentTable tbody').append(row);
        });
    });

    // Converts time in "HH:mm:ss" format to seconds
    function timeToSeconds(time) {
        var parts = time.split(':');
        return (parseInt(parts[0]) * 3600) + (parseInt(parts[1]) * 60) + parseInt(parts[2]);
    }
    // !-- Sorting by columns click --!
    // Synchronizing Value accross all CRUD (Student View > Logs Tab > Adding Log Modal)
    var triggerManual = false; //use this variable to avoid never ending loop.
    $('.selectorStudentModal').on('change', function (e) {
        if( triggerManual ) {
            return;
        }
        var selectedValues = $(this).val();
        $('.selectorStudentView').val(selectedValues);
        changeSelValues();
        //showLogs();
        updateFilteredData()
    });

    $('.selectorStudentView').on('change', function (e) {
        if( triggerManual ) {
            return;
        }
        var selectedValues = $(this).val();
        $('.selectorStudentModal').val(selectedValues);
        updateFilteredData()
        changeSelValues();
    });

    $('.viewLogsBtn').on('click', function (e) {
        var selectedStudent = $(this).val();
        $('.selectorStudentView').val(selectedStudent);
        $('.selectorStudentModal').val(selectedStudent);

        //showLogs();
        updateFilteredData()
        changeSelValues();
    })

    function changeSelValues() {
        triggerManual = true; //set the global variable as true.
        $('.selectorStudentModal').trigger('change');
        $('.selectorStudentView').trigger('change');

        triggerManual = false; //set it again to false
    }

    // --- Searching script ---
    function search() {
        // declare elements
        var searchBox = document.getElementById('searchBox');
        var table = document.getElementById("studentTable");
        //console.log(table);
        // get the first tbody, or create a new one if none exists
        var tbody = table.tBodies.length > 0 ? table.tBodies[0] : table.createTBody();
        //console.log(tbody);
        var trs = tbody.getElementsByTagName("tr");
        // Declare search string
        var filter = searchBox.value.toUpperCase();
        // Loop through rows
        for (var rowI = 0; rowI < trs.length; rowI++) {
            // define the row's cells
            var tds = trs[rowI].getElementsByTagName("td");
            // hide the row
            trs[rowI].style.display = "none";
            // loop through row cells without the last one (action tab)
            for (var cellI = 0; cellI < tds.length - 1; cellI++) {
                // if there's a match
                if (tds[cellI].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    // show the row
                    trs[rowI].style.display = "";
                    // skip to the next row
                    break;
                }
            }
        }
    }
    
    // Searching for Logs
    function searchLogs() {
        // declare elements
        const searchBox = document.getElementById('searchBox');
        const table = document.getElementById("logsTable");
        //console.log(table);
        // Declare search string
        var filter = searchBox.value.toUpperCase();
        // Loop through all tbody rows, not just the currently displayed ones
        for (var rowI = 0; rowI < table.rows.length; rowI++) {
            // define the row's cells
            var tds = table.rows[rowI].getElementsByTagName("td");
            // hide the row
            table.rows[rowI].style.display = "none";
            // loop through row cells without the last one (action tab)
            for (var cellI = 0; cellI < tds.length - 1; cellI++) {
                // if there's a match
                if (tds[cellI].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    // show the row
                    table.rows[rowI].style.display = "";
                    // skip to the next row
                    continue;
                }
            }
        }
    }
    // !-- Searching script --!

    // Dropdown sujet select
    $('#selectorPromo').on('change', function() {
        var selectedPromo = $('#selectorPromo option:selected').text();
        $('#studentTable tbody tr').each(function() {
            var rowPromo = $(this).find('td:eq(3)').text(); // Get the sujet name in the 4th column of row
            //console.log(selectedPromo);
            if (selectedPromo === 'Tous vos sujets' || selectedPromo === rowPromo) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }).change(); // Show all on load

    // --- Tabs Script ---
    function setupTabs(){
        document.querySelectorAll(".tabs_button").forEach(button => {
            button.addEventListener("click", () => {
                const sidebar = button.parentElement;
                const tabsContainer = sidebar.parentElement;
                const tabNumber = button.dataset.forTab;
                const tabToActivate = tabsContainer.querySelector(`.tabs_content[data-tab="${tabNumber}"]`); 

                sidebar.querySelectorAll(".tabs_button").forEach(button => {
                    button.classList.remove("tabs_button_active");
                });

                tabsContainer.querySelectorAll(".tabs_content").forEach(button => {
                    button.classList.remove("tabs_content_active");
                });

                button.classList.add("tabs_button_active");
                tabToActivate.classList.add("tabs_content_active");
            })
        })
    }

    document.addEventListener("DOMContentLoaded", ()=>{
        setupTabs();
    })
    // !-- Tabs Script --!

    //Creating and displaying table
    function displayRows(value) {
        const tbody = document.getElementById("tbodyLogs");

        // Clearing previous rows if any
        tbody.innerHTML = "";

        value.forEach(function(row) {
            const newElt = document.createElement("tr");
            newElt.setAttribute("id", "table_tr");

            for (var r in row) {
                if (r === 'id') {
                    continue;
                } else if (r === 'currentDate' || r === 'pseudo' || r === 'subject_name' || r === 'enterTime') {
                    let result;
                    if (r === 'enterTime') {
                    result = row[r].substr(11);
                    } else {
                    result = row[r];
                    }

                    let tdElt = document.createElement("td");
                    tdElt.textContent = result;
                    newElt.appendChild(tdElt);
                } else {
                    let tdElt = document.createElement("td");
                    tdElt.textContent = row[r];
                    newElt.appendChild(tdElt);
                }
            }

            // Create a table cell element for buttons
            const tdElement = document.createElement('td');

            // Create an "Edit" button
            // const editButton = document.createElement('button');
            // editButton.type = 'button';
            // editButton.value = row['id'];
            // editButton.classList.add('editLogBtn', 'btn', 'btn-success', 'btn-sm');
            // editButton.textContent = 'Edit';
            // tdElement.appendChild(editButton);

            // Create a "Delete" button
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.value = row['id'];

            deleteButton.classList.add('deleteLogBtn', 'btn', 'btn-danger', 'btn-sm');
            deleteButton.textContent = 'Delete ❌';
            tdElement.appendChild(deleteButton);

            newElt.appendChild(tdElement);
            tbody.appendChild(newElt);
        });
    }

</script>

</body>
</html>