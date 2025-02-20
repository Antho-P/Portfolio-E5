<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reedition d'une etiquette produit</title>
    <link href="css/tableetiqrec.css" rel="stylesheet" type="text/css" />
</head>
<body onload="document.getElementById('numero').focus();">

    <?php 
    session_start();

    // Inclusion du menu selon l'état de la session
    if(empty($_SESSION['username'])) {
        include("main.php");
    } else {
        include("main_secu.php");
    }
    include("include/authentification.php");

    // Création d'un jeton unique pour éviter la réimpression lors du rechargement
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32)); // Génère un token aléatoire
    }

    $errorMessage = ""; // Pour stocker les messages d'erreur
    $successMessage = ""; // Pour stocker le message de succès
    $details = ""; // Pour stocker les détails de l'étiquette à afficher après impression

    ?>
    <form id="formu" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <center>
            <fieldset class="form">
                <legend>Choix de l'imprimante</legend>
                <div class="form-field">
                    <label for="ipprinter"><b>Adresse IP :</b></label>
                    <input id="ipprinter" name="ipprinter" size="24" maxlength="11" type="text" required 
                           placeholder="Renseignez une adresse IP" 
                           value="<?php echo isset($_POST['ipprinter']) ? $_POST['ipprinter'] : ''; ?>" 
                           onfocus="this.placeholder = ''" 
                           onblur="this.placeholder = 'Renseignez une adresse IP '">
                </div>
            </fieldset>
            <fieldset class="form">
                <legend>Scan du produit</legend>
                <div class="form-field">
                    <label for="numero"><b>SP Unique :</b></label>
                    <input id="numero" name="numero" size="24" maxlength="11" type="text" required placeholder="Scannez le produit">
                </div><br>
                <div class="form-field">
                    <button type="submit" class="btn btn-noir" style="height:60px; width:150px; background-color:#CC0033;">
                        <b>IMPRIMER</b>
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="reset" value="Reset" style="height:60px; width:150px; background-color:#A4A2A2;">
                        <b>EFFACER</b>
                    </button>
                </div>
            </fieldset>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" /> <!-- Jeton anti-rafraîchissement -->
            <br/>
        </center>
    </form>
    <?php

    if (isset($_POST['numero']) && isset($_POST['token'])) {
        // Vérification du jeton
        if ($_POST['token'] !== $_SESSION['token']) {
            $errorMessage = "Action d&eacute;j&agrave; effectu&eacute;e !";
        } else {
            $numero = $_POST['numero'];
            $ipprinter = $_POST['ipprinter']; // Récupération de l'IP saisie
            $NUM = $numero; // Sauvegarde de la variable $NUM pour l'impression

            // Vérification du préfixe SP
            if ((substr($numero, 0, 2)) !== 'SP') {
                $errorMessage = "SP INCONNU";
            } else {
                $trimmed = trim($numero);

                // Requête SQL pour récupérer les informations du produit
                $sql = "SELECT a.ART_ALPHA9 AS ART_ALPHA9, a.art_code AS ART_CODE, a.ART_DESL AS ART_DESL, a.ART_CCLI AS ART_CCLI, s.STK_NOSU AS STK_NOSU 
                        FROM STK_DAT s 
                        INNER JOIN ART_PAR a ON a.ART_CODE=s.ART_CODE 
                        WHERE s.STK_NoSU='" . $trimmed . "' 
                        GROUP BY a.ART_ALPHA9, a.art_code, a.ART_DESL, a.ART_CCLI, s.STK_NOSU";

                // Requête pour récupérer le jour de l'année (NUM)
                $sql1 = "SELECT max(datename(y,MVT_CRDA)) as NUM from MVT_DAT where STK_NoSU='".$trimmed."' and tmv_code in ('10010','10030') ";

                $stmt = sqlsrv_query($conn, $sql);
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                $rows = sqlsrv_has_rows($stmt);
                if ($rows == false) {
                    echo ("<center><h4>PRODUIT NON EN STOCK -- RE-EDITION IMPOSSIBLE</h4></center>");
                } else {
                    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                    $ART_ALPHA9 = $row['ART_ALPHA9'];
                    $ART_CODE = $row['ART_CODE'];
                    $ART_DESL = $row['ART_DESL'];
                    $ART_CCLI = $row['ART_CCLI'];
                    $STK_NOSU = $row['STK_NOSU'];

                    $stmt1 = sqlsrv_query($conn, $sql1);
                    $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
                    $NUM = $row1['NUM'];

                    // Connexion à l'imprimante ZPL
                    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

                    // Vérifier si le socket a été créé avec succès
                    if (!$sock) {
                        $errorMessage = "Erreur : Impossible de cr&eacute;er le socket.";
                    } else {
                        // Définir un délai d'attente de 2 secondes pour la connexion
                        socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 2, 'usec' => 0]);
                        socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 2, 'usec' => 0]);

                        // Tentative de connexion
                        if (!@socket_connect($sock, $ipprinter, xxxx)) { // Le @ supprime l'affichage immédiat des erreurs (XXXX = port)
                            $errorcode = socket_last_error();
                            $errormsg = socket_strerror($errorcode);
                            $errorMessage = "Erreur de connexion &agrave; l'imprimante : $ipprinter<br>V&eacute;rifiez le statut de connexion de l'imprimante.";
                        } else {
                            // Si la connexion réussit, supprimer le jeton
                            unset($_SESSION['token']);

                            // Préparation du ZPL pour l'impression
                            $zpl = "^XA^PW832^LH0,0^FS^POI^CF0,36^FO440,000^A0N,115,130^FH^FD$ART_ALPHA9^FS"
                                . "^FO410,100^ADN,18,10^FH^FD$ART_CODE^FS"
                                . "^FO410,120^ABN,11,07^FH^FD$ART_DESL^FS"
                                . "^FO720,000^A0R,60,40^FH^FD$ART_CCLI^FS"    
                                . "^FO410,140^BY2^BCN,090,Y,N^FD$STK_NOSU^FS"
                                . "^FO740,125^AVN,30,36^FH^FD^FS"
                                . "^FO740,190^AVN,30,36^FH^FD^FS"
                                . "^FO690,235^ADN,18,10^FH^FD$NUM^FS"
                                . "^RS,,,3,N^RR3,1^RFW,A,2,,A^FD$STK_NOSU^FS^XZ";

                            // Envoi des données
                            socket_send($sock, $zpl, strlen($zpl), 0);
                            socket_close($sock);

                            // Stockage des détails pour l'affichage après impression
                            $details = "<b>D&eacute;tails de l'&eacute;tiquette :</b><br>"
                            . "<p style='text-align: center; margin: 0;'>Code article : $ART_CODE</p>"
                            . "<p style='text-align: center; margin: 0;'>Description : $ART_DESL</p>"
                            . "<p style='text-align: center; margin: 0;'>Client : $ART_CCLI</p>"
                            . "<p style='text-align: center; margin: 0;'>Num&eacute;ro de stock : $STK_NOSU</p>"
                            . "<p style='text-align: center; margin: 0;'>Article Alpha9 : $ART_ALPHA9</p>"
                            . "<p style='text-align: center; margin: 0;'>Num&eacute;ro : $NUM</p>";


                            $successMessage = "Impression r&eacute;ussie.";
                        }
                    }
                }
            }
        }
    }

    if ($errorMessage) {
        echo "<center><h4 style='color:red;'>$errorMessage</h4></center>";
    }

    if ($successMessage) {
        echo "<center><h4 style='color:green;'>$successMessage</h4></center>";
        echo "<center>$details</center>";
    }
sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn); 

    ?>
</body>
</html>