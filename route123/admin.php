<?php
require_once("../PHP/config.php");
require_once("../PHP/download.php");

if(!$_SESSION['connected'] || !$_SESSION['admin']){
        header("Location: ../index.php");
} elseif ($_SESSION['admin'] && $_SESSION['pseudo'] !== "admin") {
        header("Location: crudprof.php");
}else{
    header("Location: crud.php");
}
createCSV();
?>

<!DOCTYPE html>

<html lang="fr">
<head>
        <meta charset="utf-8">
        <title>CSM</title>
        <link rel="stylesheet" href="../CSS/admin_z01.css">
        <script src="../JS/admin_z01.js"></script>
        <link rel="shortcut icon" href="#" >
</head>
<body onload="setActualDate();">
        <?php
                if(isset($_SESSION['user_info'])){
                        echo ("<p>" . $_SESSION['user_info'] . "</p>");
                        unset($_SESSION['user_info']);
                }
        ?>
        <div class="hour">
                <label class="label_student">Student: </label>
                <select id="selectorStudent" onChange="action()">
                        <option>Apprenant</option>
                        <?php getStudents(); ?>
                </select>
                <select id="selectorMonth" onChange="action()">
                        <option>Mois</option>
                        <?php getMonth(); ?>
                </select>
                <select id="selectorYear" onChange="action()">
                        <option>Année</option>
                        <option>2022</option>
                        <option>2023</option>
                </select>


                <table>
                        <thead>
                                <tr>
                                        <td>ID</td>
                                        <td>DATE</td>
                                        <td>HOUR ENTER</td>
                                        <td>HOUR EXIT</td>
                                        <td>TIME IN</td>
                                </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                </table>
        </div>
        <div class="info_download">
                <div class="info">
                        <p>Nombre de jours présents : <span id="nb_jour_present"></span></p>
                        <p>Nombre d'heure présent : <span id="nb_heure"></span></p>
                </div>
                <div class="download">
                        <a href="../DATA/data.csv" download><button class="download_button">Download Data</button></a>
                </div>
        </div>
        <div class="inline-forms">
                <div class="form_add_user">
                        <form class="add_user" action="../PHP/add_user.php" method="POST">
                                <table>
                                        <tr>
                                                <td>
                                                        <label>PSEUDO</label>
                                                </td>
                                                <td>
                                                        <input type="text" name="pseudo" required>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>EMAIL</label>
                                                </td>
                                                <td>
                                                        <input type="text" name="email" required>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>NOM</label>
                                                </td>
                                                <td>
                                                        <input type="text" name="nom" required>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>PRÉNOM</label>
                                                </td>
                                                <td>
                                                        <input type="text" name="prenom" required>
                                                </td>
                                        </tr>
                                </table>
                                <input class="form_button" type="submit" name="submit" value="ADD">
                        </form>
                </div>

                <div class="edit_log">
                        <form class="" action="../PHP/edit_log.php" method="POST" novalidate>
                                <table>
                                        <tr style="display:none">
                                                <td>
                                                        <input type="text" value="" id="idStudent" name="idStudent"></label>
                                                </td>
                                        </tr>
                                        <tr style="display:none">
                                                <td>
                                                        <input type="text" value="" id="selectedIndexStudent" name="selectedIndexStudent"></label>
                                                        <input type="text" value="" id="selectedIndexMonth" name="selectedIndexMonth"></label>
                                                        <input type="text" value="" id="selectedIndexYear" name="selectedIndexYear"></label>
                                                        <input type="time" value="" id="actHourEnterEdit" name="actHourEnterEdit"></label>
                                                        <input type="time" value="" id="actHourExitEdit" name="actHourExitEdit"></label>
                                                </td>
                                        </tr>

                                        <tr>
                                                <td>
                                                        <label>DATE</label>
                                                </td>
                                                <td>
                                                        <input type="date" value="" id="oldDate" name="oldDate" required></label>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>HOUR ENTER</label>
                                                </td>
                                                <td>
                                                        <input type="time" value="" id="oldHourEnter" name="oldHourEnter" step="1" required></label>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>HOUR EXIT</label>
                                                </td>
                                                <td>
                                                        <input type="time" value="" id="oldHourExit" name="oldHourExit" step="1"></label>
                                                </td>
                                        </tr>
                                </table>
                                <input class="form_button" type="submit" name="submit" value="Edit">
                        </form>
                </div>

                <div class="add_log">
                        <form class="" action="../PHP/add_log.php" method="POST">
                                <table>
                                        <tr style="display:none">
                                                <td>
                                                        <input type="text" value="" id="selectedIndexStudentAdd" name="selectedIndexStudent"></label>
                                                        <input type="text" value="" id="selectedIndexMonthAdd" name="selectedIndexMonth"></label>
                                                        <input type="text" value="" id="selectedIndexYearAdd" name="selectedIndexYear"></label>
                                                        <input type="time" value="" id="actHourEnter" name="actHourEnter"></label>
                                                        <input type="time" value="" id="actHourExit" name="actHourExit"></label>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>STUDENT ID</label>
                                                </td>
                                                <td>
                                                        <input type="text" value="" id="studentId" name="studentId" required></label>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>OLD DATE</label>
                                                </td>
                                                <td>
                                                        <input type="date" value="" id="currentDate" name="currentDate" required></label>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>HOUR ENTER</label>
                                                </td>
                                                <td>
                                                        <input type="time" value="" id="hourEnter" name="hourEnter" step="1" required></label>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>
                                                        <label>HOUR EXIT</label>
                                                </td>
                                                <td>
                                                        <input type="time" value="" id="hourExit" name="hourExit" step="1"></label>
                                                </td>
                                        </tr>
                                </table>
                                <input class="form_button" type="submit" name="submit" value="Add">
                        </form>
                </div>

        </div>

</body>
</html>

<?php

function getStudents(){
        $pdo = getPDO();
        $q = $pdo->prepare("SELECT id, nom, prenom, pseudo from students WHERE admin = false ORDER BY nom, prenom");
        $q->execute();
        while($res = $q->fetch()){
                echo("<option value=". $res['id'] . ">". $res['nom']. " "  . $res['prenom']. " (". $res['pseudo'].")</option>");
        }
}

function getMonth(){
        $month = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        foreach($month as $m){
                echo("<option>".$m."</option>");
        }
}

// Setting back old selection
if(isset($_SESSION['selectedIndexStudent']) && isset($_SESSION['selectedIndexMonth']) && isset($_SESSION['selectedIndexYear'])){
        echo "<script>";
        echo "let selectorStudent = document.getElementById('selectorStudent');";
        echo "let selectorMonth = document.getElementById('selectorMonth');";
        echo "let selectorYear = document.getElementById('selectorYear');";
        echo "selectorStudent.selectedIndex = " . $_SESSION['selectedIndexStudent'] . ";";
        echo "selectorMonth.selectedIndex = " . $_SESSION['selectedIndexMonth'] . ";";
        echo "selectorYear.selectedIndex = " . $_SESSION['selectedIndexYear'] . ";";
        echo "const e = new Event('change');";
        echo "selectorStudent.dispatchEvent(e);";
        echo "</script>";
        unset($_SESSION['selectedIndexStudent']);
        unset($_SESSION['selectedIndexMonth']);
        unset($_SESSION['selectedIndexYear']);
}

?>