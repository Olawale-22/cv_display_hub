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
        
    </style>

<script>
                // create a WebSocket connection to the server
                //var socket = new WebSocket("ws://10.10.0.21:8282/websockets/websocket_server.php");
                var socket = new WebSocket("ws://localhost:8282/emergement_csm/websockets/websocket_server.php");

                var ps = "<?php echo $_SESSION['pseudo']; ?>";
                        console.log("ps: ", ps);
                        var Uid = "<?php echo $_SESSION['id']; ?>";
                        console.log("Uid: ", Uid);

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
                try {
                        const data = JSON.parse(event.data); // Parse the JSON string into an object

                        if (data.message.substr(0, 7) === "subject") {
                                const subjectName = data.message.substr(8);
                                const adName = data.adminName;
                                const setId = data.id;
                                console.log("setId: ", setId);
                                console.log("subjectName: ", subjectName);

                                                const buttonElement = document.createElement("button");
                                                buttonElement.setAttribute("id", data.sId);

                                                buttonElement.style.backgroundColor = 'hsl(216, 96%, 52%)';
                        buttonElement.style.color = "white";
                        buttonElement.style.padding = "10px";
                        buttonElement.style.marginTop = "1%";
                        buttonElement.style.border = "none";
                        buttonElement.style.fontSize = "0.8rem";
                        buttonElement.style.display = "flex";
                        buttonElement.style.flexDirection = "column";
                        buttonElement.style.textTransform = "uppercase";

                                                buttonElement.style.backgroundColor = 'hsl(216, 96%, 52%)';
                                                buttonElement.innerHTML = adName + " => " + subjectName + " in session";
                        buttonElement.disabled = true;

                                                const containerElement = document.getElementById("subscribers-list");
                                                containerElement.appendChild(buttonElement);

                        } else if (data.message.substr(0, 9) === "heartbeat") {
                                // Rest of the code remains the same
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
                socket.onerror = function(event) {
                console.log("WebSocket error: " + event);
                };

                socket.onclose = function(event) {
                console.log("WebSocket connection closed: " + event);
                };

        </script>
    <!-- School Icon -->
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">

    <title>CSM Punctuality - ADMIN</title>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../CSS/crud.css">
</head>
<body>

<!-- Add Utilisateur -->
<div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title txt" id="exampleModalLabel">New Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveStudent">
                <div class="modal-body">

                    <div id="errorMessageAddStudent" class="alert alert-warning d-none"></div>

                    <div class="mb-3 txt">
                        <label for="">Pseudo</label>
                        <input type="text" name="pseudo" class="form-control" />
                    </div>
                    <div class="mb-3 txt">
                        <label for="">Nom</label>
                        <input type="text" name="last_name" class="form-control" />
                    </div>
                    <div class="mb-3 txt">
                        <label for="">Prénom</label>
                        <input type="text" name="first_name" class="form-control" />
                    </div>
                    <div class="mb-3 txt">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" />
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="checkAsAdmin" name="checkAsAdmin" />
                        <label class="form-check-label" for="checkAsAdmin">Faire de l’utilisateur un VIP ?</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Save User</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Edit Utilisateur</h5>
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
                    <div class="mb-3">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="">Promotion</label>
                        <br>
                        <select id="edit_promotion_id" class="selectorPromotionStudent" name="promo_id" style="width: 100%">
                            <?php getPromos(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Update User ✅</button>
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
                        <label for="">Utilisateur</label>
                        <select id="logSelectorStudent" class="selectorStudentModal" name="studentId">
                            <?php getStudents(); ?>
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
                    <div class="mb-3">
                        <label for="exitTime">Exit Time</label>
                        <input type="time" step="1" name="exitTime" class="form-control" />
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

<!-- Edit Log Modal -->
<div class="modal fade" id="logEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateLog">
                <div class="modal-body">

                    <div id="errorMessageUpdateLog" class="alert alert-warning d-none"></div>

                    <input type="hidden" name="log_id" id="log_id" >

                    <div class="mb-3">
                        <label for="">Date</label>
                        <input type="date" name="date" id="date" class="form-control" readonly/>
                    </div>
                    <div class="mb-3">
                        <label for="">Enter Time</label>
                        <input type="time" step="1" name="enterDate" id="enterDate" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label for="">Exit Time</label>
                        <input type="time" step="1" name="exitDate" id="exitDate" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Update Log ✅</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Promo -->
<div class="modal fade" id="promoAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content blueBg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="savePromo">
                <div class="modal-body">

                    <div id="errorMessageAddPromo" class="alert alert-warning d-none"></div>

                    <div class="mb-3">
                        <label for="">Intitulé Promo</label>
                        <input type="text" name="nom_promo" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Save Promo</button>
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
            <form id="updatePromo">
                <div class="modal-body">

                    <div id="errorMessageUpdateStudent" class="alert alert-warning d-none"></div>

                    <input type="hidden" name="promo_id" id="promo_id" >

                    <div class="mb-3">
                        <label for="">Intitulé Promo</label>
                        <input type="text" name="nom_promo" id="nom_promo" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary redButton" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary blueButton">Update Promo ✅</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View (Students) -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card tabs">
                <div class="card-header tabs_sidebar">
                    <button class="tabs_button blueButton" data-for-tab="4">Live</button><button class="tabs_button tabs_button_active blueButton" data-for-tab="1">Utilisateurs</button><button class="tabs_button blueButton" data-for-tab="2">Logs</button><button class="tabs_button blueButton" data-for-tab="3">Promo</button>
                    <button class="btn btn-danger float-end redButton deconnexion" onclick="location.href='../PHP/deco.php'" >Deconnexion</button>
                    <button class="btn btn-primary float-end blueButton" id="download-button">Download Data</button>
                </div>
                <div class="card-body tabs_content tabs_content_active" data-tab="1">
                    <div class="card-filter">
                        <select id="selectorPromo" class="selectorPromoView" name="promoId">
                            <option value="">Toutes les promotions</option>
                            <?php getPromos(); ?>
                       </select>
                        <input type="text" id="searchBox" onkeyup="search()" placeholder="Search...">
                        <button type="button" class="btn btn-primary float-end blueButton" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                            Add Student
                        </button>
                    </div>
                    <table id="studentTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Pseudo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
<!--                        <th>Email</th>-->
                            <th>Heures du mois actuel en Sessions
                                <?php
                                // Get the current month and year
                                $month = date('m');
                                $year = date('Y');

                                // Get the number of working days in the current month
                                $working_days = countWorkingDays($month, $year);

                                // Calculate the number of hours
                                $hours = $working_days * 7;

                                echo "<br>";
                                echo "(" . $working_days . "j/" . $hours . "h)";

                                // Function to count the number of working days in a month
                                function countWorkingDays($month, $year) {
                                    $workingDays = 0;
                                    $totalDays = date('t', strtotime("$year-$month-01"));
                                    for ($day = 1; $day <= $totalDays; $day++) {
                                        $date = date("$year-$month-$day");
                                        $dayOfWeek = date('N', strtotime($date));
                                        if ($dayOfWeek <= 5) {
                                            $workingDays++;
                                        }
                                    }
                                    return $workingDays;
                                }
                                ?>
                            </th>
                            <th>Promotion</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $pdo = getPDO();
                        $query = "
                        SELECT students.id AS studentId, id_promo, pseudo, prenom, nom, mail, nom_promo, COALESCE(SEC_TO_TIME(SUM(TIME_TO_SEC(timeIn))), '00:00:00') as total_time_in
                        FROM students
                        LEFT JOIN promo ON students.id_promo = promo.id
                        LEFT JOIN logs ON students.id = logs.idStudent AND logs.currentDate >= DATE_FORMAT(NOW(), '%Y-%m-01')
                        WHERE NOT students.id = 1
                        GROUP BY students.id
                        ORDER BY nom, prenom DESC
                        ";
                        $stmt = $pdo->query($query);
                         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if(count($result) > 0)
                                {
                                 foreach($result as $student)
                                {
                                ?>
                                <tr>
                                    <td><?= $student['pseudo'] ?></td>
                                    <td><?= $student['nom'] ?></td>
                                    <td><?= $student['prenom'] ?></td>
<!--                                <td>--><?php //= $student['mail'] ?><!--</td>-->
                                    <td><?= $student['total_time_in'] ?></td>
                                    <td><?= $student['nom_promo'] ?></td>
                                    <td>
                                        <button type="button" value="<?=$student['studentId'];?>" class="viewStudentBtn btn btn-primary btn-sm blueButton">Logs 📄</button>
                                        <button type="button" value="<?=$student['studentId'];?>" class="editStudentBtn btn btn-success btn-sm">Edit 🖊️</button>
                                        <button type="button" value="<?=$student['studentId'];?>" class="deleteStudentBtn btn btn-danger btn-sm redButton">Delete ❌</button>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php

                function getStudents(){
                    $pdo = getPDO();
                    $q = $pdo->prepare("SELECT id, nom, prenom, pseudo from students WHERE admin = true ORDER BY nom, prenom");
                    $q->execute();
                    while($res = $q->fetch()){
                        echo("<option value=". $res['id'] . ">". $res['nom']. " "  . $res['prenom']. " (". $res['pseudo'].")</option>");
                    }
                }

                function getPromos(){
                    $pdo = getPDO();
                    $q = $pdo->prepare("SELECT id, nom_promo from promo ORDER BY id");
                    $q->execute();
                    while($res = $q->fetch()){
                        echo("<option value=". $res['id'] . ">". $res['nom_promo']. "</option>");
                    }
                }

                ?>
                <div class="card-body tabs_content" data-tab="2">
                    <div class="card-filter">

                        <select id="selectorStudent" class="selectorStudentView" name="studentId">
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
                            <option>2022</option>
                            <option>2023</option>
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
                            <th>Enter Time</th>
                            <th>Exit Time</th>
                            <th>Time In</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyLogs">

                        </tbody>
                    </table>

                </div>

                <div class="card-body tabs_content" data-tab="4">
                    <h2>SESSIONS EN DIRECT ICI...</h2><div id="heart-container"></div><br>
                    <div id="subscribers-list" class="subs-list">
                    </div>

                </div>

                <div class="card-body tabs_content" data-tab="3">
                    <div class="card-filter">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#promoAddModal">
                            Add Promo
                        </button>
                    </div>
                    <table id="promoTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la promo</th>
                            <th>Nombre d'élèves</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $pdo = getPDO();
                        $query = "SELECT p.id, p.nom_promo, COUNT(s.id) AS number_of_students FROM promo p LEFT JOIN students s ON p.id = s.id_promo GROUP BY p.id, p.nom_promo ORDER BY p.id";
                        $stmt = $pdo->query($query);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if(count($result) > 0)
                        {
                        foreach($result as $promo)
                        {
                        ?>
                                <tr>
                                <td><?= $promo['id'] ?></td>
                                <td><?= $promo['nom_promo'] ?></td>
                                <td><?= $promo['number_of_students']?></td>
                                <td>
                                <button type="button" value="<?=$promo['id'];?>" class="viewPromoBtn btn btn-primary btn-sm">Utilisateurs 👨💻</button>
                                <button type="button" value="<?=$promo['id'];?>" class="editPromoBtn btn btn-success btn-sm">Edit 🖊️</button>
                                <button type="button" value="<?=$promo['id'];?>" class="deletePromoBtn btn btn-danger btn-sm">Delete ❌</button>
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
    // On load
    $(document).ready(function(){
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
    })

    //Adding Student / Utilisateur
    $(document).on('submit', '#saveStudent', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_student", true);

        $.ajax({
            type: "POST",
            url: "crud_action.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageAddStudent').removeClass('d-none');
                    $('#errorMessageAddStudent').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageAddStudent').addClass('d-none');
                    $('#studentAddModal').modal('hide');
                    $('#saveStudent')[0].reset();

                    alertify
                        .alert("Utilisateur Créé", res.message, function(){
                        });

                    //Refreshing table content after adding
                    //$('#studentTable').load(location.href + " #studentTable");
                    $.ajax({
                        url: location.href,
                        success: function(data) {
                            var table = $(data).find('#studentTable tbody');
                            $('#studentTable tbody').replaceWith(table);

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
            url: "crud_action.php?student_id=" + student_id,
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
                    $('#email').val(res.data.mail);
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
            url: "crud_action.php",
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
    $(document).on('click', '.viewStudentBtn', function () {
        //We set value of student select to the one we clicked
        var student_pseudo = $(this).val();

        var studentSelect = document.querySelector('#selectorStudent');
        var logStudentSelect = document.querySelector('#logSelectorStudent');
        studentSelect.value = logStudentSelect.value = student_pseudo;

        //Then we go on the logs tab
        var logsTab = document.querySelector('body > div.container.mt-4 > div > div > div > div.card-header.tabs_sidebar > button:nth-child(2)')
        logsTab.click();

        //We refresh content of log tab
        updateFilteredData();
    });

    // Delete Student (modal)
    $(document).on('click', '.deleteStudentBtn', function (e) {
        e.preventDefault();

        if(confirm('Voulez-vous vraiment supprimer cet utilisateur ?'))
        {
            var student_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "crud_action.php",
                data: {
                    'delete_student': true,
                    'student_id': student_id
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
        //Refreshing filter by promo
        var selectedPromo = $('#selectorPromo option:selected').text();
        $('#studentTable tbody tr').each(function() {
            var rowPromo = $(this).find('td:eq(4)').text(); // Get the promo name in the 4th column of row
            //console.log(selectedPromo);
            if (selectedPromo === 'Toutes les promotions' || selectedPromo === rowPromo) {
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
            url: "crud_action.php",
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

                    updateFilteredData();
                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });

    // Edit Log (modal)
    $(document).on('click', '.editLogBtn', function () {

        var log_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "crud_action.php?log_id=" + log_id,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 404) {

                    alert(res.message);
                }else if(res.status == 200){
                    $enterDateTime = res.data.enterDate;
                    if($enterDateTime !== null){
                        $enterDateTime = $enterDateTime.substring(11);
                    }

                    $exitDateTime = res.data.exitDate;
                    if($exitDateTime !== null){
                        $exitDateTime = $exitDateTime.substring(11);
                    }

                    $('#log_id').val(res.data.id);
                    $('#date').val(res.data.currentDate);
                    $('#enterDate').val($enterDateTime);
                    $('#exitDate').val($exitDateTime);

                    $('#logEditModal').modal('show');
                }

            }
        });

    });

    // Edit Log (submit)
    $(document).on('submit', '#updateLog', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("update_log", true);

        $.ajax({
            type: "POST",
            url: "crud_action.php",
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

                    updateFilteredData();

                }else if(res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });

    // Delete Log (modal)
    $(document).on('click', '.deleteLogBtn', function (e) {
        e.preventDefault();

        if(confirm('Voulez-vous vraiment supprimer ce journal?'))
        {
            var log_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "crud_action.php",
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

                        updateFilteredData();
                    }
                }
            });
        }
    });

    // -------------- Promo --------------
    //Adding Promo
    $(document).on('submit', '#savePromo', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_promo", true);

        $.ajax({
            type: "POST",
            url: "crud_action.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageAddPromo').removeClass('d-none');
                    $('#errorMessageAddPromo').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageAddPromo').addClass('d-none');
                    $('#promoAddModal').modal('hide');
                    $('#savePromo')[0].reset();

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

        var promo_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "crud_action.php?promo_id=" + promo_id,
            success: function (res) {
                //console.log(response);
                //var res = jQuery.parseJSON(response);
                if(res.status == 404) {
                    alert(res.message);
                }else if(res.status == 200){

                    //console.log(res.data)
                    $('#promo_id').val(res.data.id);
                    $('#nom_promo').val(res.data.nom_promo);

                    $('#promoEditModal').modal('show');
                }

            }
        });

    });

    // Edit Promo (submit)
    $(document).on('submit', '#updatePromo', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("update_promo", true);

        $.ajax({
            type: "POST",
            url: "crud_action.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //console.log(response);
                // var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    $('#errorMessageUpdatePromo').removeClass('d-none');
                    $('#errorMessageUpdatePromo').text(res.message);

                }else if(res.status == 200){

                    $('#errorMessageUpdatePromo').addClass('d-none');

                    alertify.set('notifier','position', 'top-right');
                    alertify.success(res.message);

                    $('#promoEditModal').modal('hide');
                    $('#updatePromo')[0].reset();

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

    // View Promo (redirect to students page from this promo)
    $(document).on('click', '.viewPromoBtn', function () {
        //We set value of student select to the one we clicked
        var id_promo = $(this).val();

        var promoSelect = document.querySelector('#selectorPromo');
        promoSelect.value = id_promo;

        //Then we go on the logs tab
        var studentTab = document.querySelector('body > div.container.mt-4 > div > div > div > div.card-header.tabs_sidebar > button:nth-child(1)')
        studentTab.click();

        //We refresh content of log tab
        updateFilteredData();
        //We also refresh the table according to the filter
        filterBack();
    });

    // Delete Promo (modal)
    $(document).on('click', '.deletePromoBtn', function (e) {
        e.preventDefault();

        if(confirm('Voulez-vous vraiment supprimer cette promotion ?'))
        {
            var promo_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "crud_action.php",
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
        updateFilteredData();
    });

    $('.selectorStudentView').on('change', function (e) {
        if( triggerManual ) {
            return;
        }
        var selectedValues = $(this).val();
        $('.selectorStudentModal').val(selectedValues);
        updateFilteredData();
        changeSelValues();
    });
    
    $('.viewStudentBtn').on('click', function (e) {
        var selectedStudent = $(this).val();
        $('.selectorStudentView').val(selectedStudent);
        $('.selectorStudentModal').val(selectedStudent);

        updateFilteredData();
        changeSelValues();
    })

    function changeSelValues() {
        triggerManual = true; //set the global variable as true.
        $('.selectorStudentModal').trigger('change');
        $('.selectorStudentView').trigger('change');

        triggerManual = false; //set it again to false
    }
    // !-- Select2 --!

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

    // Dropdown promo select
    $('#selectorPromo').on('change', function() {
        var selectedPromo = $('#selectorPromo option:selected').text();
        $('#studentTable tbody tr').each(function() {
            var rowPromo = $(this).find('td:eq(4)').text(); // Get the promo name in the 4th column of row
            //console.log(selectedPromo);
            if (selectedPromo === 'Toutes les promotions' || selectedPromo === rowPromo) {
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

        var ps = "<?php echo $_SESSION['pseudo']; ?>";
        console.log("ps: ", ps);
        // user_id
        var Uid = "<?php echo $_SESSION['id']; ?>";
        console.log("Uid: ", Uid);
        
    // filter logs here
    function updateFilteredData() {
        //var promoId = document.getElementById("selectoPromo").value;
        var studentId = document.getElementById("selectorStudent").value;
        var logsMonth = document.getElementById("selectorMonth").value;
        var selectorYear = document.getElementById("selectorYear").value;

        // Send an AJAX request to fetch filtered data
        fetch("crud_action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "studentId=" + studentId + "&logsMonth=" + logsMonth + "&selectorYear=" + selectorYear
        })
        .then(function(response) {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error("Error: " + response.status);
            }
        })
        .then(function(data) {
        // Update the table body with the fetched data
        console.log(data);
            var jsonData = JSON.parse(data);
            console.log(jsonData);
            // Update the table body with the fetched data
            displayRows(jsonData);
        })
        .catch(function(error) {
            console.error(error);
        });
    }

    // Add event listeners to the logs filter elements
    //document.getElementById("selectoPromo").addEventListener("change", updateFilteredData);
    document.getElementById("selectorStudent").addEventListener("change", updateFilteredData);
    document.getElementById("selectorMonth").addEventListener("change", updateFilteredData);
    document.getElementById("selectorYear").addEventListener("change", updateFilteredData);

    // Initial fetch
    updateFilteredData();

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
                } else if (r === 'enterDate' || r === 'currentDate' || r === 'exitDate' || r === 'timeIn' || r === 'pseudo') {
                    let result;
                    if (r === 'timeIn') {
                        if(!row[r]){
                            result = ""
                        } else {
                            result = row[r];
                        }
                    } else if (r === "currentDate" || r === "pseudo") {
                        result = row[r];
                    }else {
                        if (!row[r]){
                            result = "";
                        } else{
                            result = row[r].substr(11);
                        }
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

            //Create "Edit" button
            const editButton = document.createElement('button');
            editButton.type = 'button';
            editButton.value = row['id'];
            editButton.classList.add('editLogBtn', 'btn', 'btn-success', 'btn-sm');
            editButton.textContent = 'Edit';
            tdElement.appendChild(editButton);

            // Create a "Delete" button
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.value = row['id'];

            deleteButton.classList.add('deleteLogBtn', 'btn', 'btn-danger', 'btn-sm');
            deleteButton.style.marginLeft = '4%';
            deleteButton.textContent = 'Delete ❌';
            tdElement.appendChild(deleteButton);

            newElt.appendChild(tdElement);
            tbody.appendChild(newElt);
        });
    }
    // !-- Fetching Student Data --!

</script>

</body>
</html>