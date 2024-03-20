<?php
require_once('../PHP/config.php');

if (!isset($_SESSION['connected']) || !$_SESSION['connected']) {
    header("Location: ../index.php");
    exit();
}

function getCours()
{
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, nom_sujet FROM sujet ORDER BY id");
    $q->execute();
    $results = $q->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $res) {
        echo '<div>';
        echo '<input type="checkbox" id="promo_' . $res['id'] . '" name="promo_id[]" value="' . $res['id'] . '">';
        echo '<label for="promo_' . $res['id'] . '">' . $res['nom_sujet'] . '</label>';
        echo '</div>';
    }
}
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Global Subscriptions</title>
    <meta name="description" content="Courses Subscription Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../CSS/sign_up.css">
    <style>
        .containerProfile{
            background: #00729c;
            border-radius: 0% 8% 0% 8%;
            width: 350px;
            height: 'fit-content';
            padding-bottom: 20px;
            position: absolute;
            top:35%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: auto;
            padding: 40px 50px 20px 50px;
        }
        
        .new {
            margin-top: 15%;
            margin-bottom: 40%;
        }

        @media (max-width: 760px) { .new {
            margin-top: 50%;
            margin-bottom: 40%;
        } }
    </style>

</head>
<body>
    <center>
        <div class="containerProfile new">
            <div class="card">
                <div class="card-header"><strong>Veuillez vous abonner aux cours dans votre promo</strong></div>
                <form id="saveProfileForm">
                    <div class="card-body card-block">
                        <div class="mb-3">
                            <label for="" class="form-control-label form-group"><strong><u>Cochez</u></strong></label>
                            <br>
                            <?php getCours(); ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p style="float: left; font-size: 1rem;">Avez-vous suivi tous les cours nécessaires ?</p>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-dot-circle-o"></i> Oui, Submit
                        </button>
                        <input type="hidden" name="save_pro_sujets" value="true">
                    </div>
                </form>
                <?php
                    if (isset($_SESSION['error'])) {
                        echo "<p class=\"p_error\">" . $_SESSION['error'] . "</p>";
                        unset($_SESSION['error']);
                    }
                ?>
            </div>
        </div>
    </center>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $(document).on('submit', '#saveProfileForm', function (e) {
                e.preventDefault();

                var Pseuddo = "<?php echo $_SESSION['pseudo']; ?>";
                console.log("Pseudo: ", Pseuddo);

                var formData = new FormData(this);
                formData.append('pseudo', Pseuddo);
                console.log("FormData: ", formData);

                formData.append('save_pro_sujets', true);

                $.ajax({
                    type: "POST",
                    url: "signup_profile.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (res) {
                        // Handle the response here
                        if (res.status == 200) {
                            alert(res.message);
                            window.location.href = "../index.php";
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
