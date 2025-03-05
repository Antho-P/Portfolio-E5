<?php 


session_start();

if(empty($_SESSION['user']) )
  include("main.php");
else
 include("main_secu.php");

$ipprinter = '10.28.X.X' ; /*Remplacez par l'adresse IP de l'imprimante */

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="css/style.css" rel="stylesheet" type="text/css" />
  <link href="css/tablesorterA.css" rel="stylesheet" type="text/css" />
        <p style="text-align: right;">
<?php 
  echo("<b>Imprimante : $ipprinter</b>");
?>
        </p>
    <body onload="focus()"></body>
 
  <style>
     /* Style pour ajuster l'image au tableau */
    .printer-img {
      width: 30px;
      height: 30px; 
    }
    </style>

     <script>
      // Fonction pour soumettre le formulaire lors du clic sur l'image d'impression
      function submitForm(sp, art_alpha9, art_code, art_desl, art_ccli, num) {
          let form = document.createElement('form');
          form.method = 'POST';
          form.action = '';

          // Ajout des champs cachés au formulaire
          const fields = { SP: sp, ART_ALPHA9: art_alpha9, ART_CODE: art_code, ART_DESL: art_desl, ART_CCLI: art_ccli, NUM: num };
          for (let key in fields) {
              let input = document.createElement('input');
              input.type = 'hidden';
              input.name = key;
              input.value = fields[key];
              form.appendChild(input);
          }

          // Désactiver tous les boutons d'impression
          let printButtons = document.querySelectorAll('.printer-img');
          printButtons.forEach(button => {
              button.style.pointerEvents = 'none'; // Désactiver les événements de clic
              button.style.opacity = '0.5'; // Rendre visuellement inactif
          });

          document.body.appendChild(form);
          form.submit(); // Soumission du formulaire

          // Réactiver tous les boutons d'impression après 3 secondes (ou à la réception du succès d'impression)
          setTimeout(() => {
              printButtons.forEach(button => {
                  button.style.pointerEvents = 'auto'; // Réactiver les événements de clic
                  button.style.opacity = '1'; // Remettre à l'état visuel actif
              });
          }, 3000); // 3 secondes par exemple, ajustez selon vos besoins
      }

    </script>
</head>
<body>

<?php
include("include/authentification.php");

if( $conn ) {
     echo ".";
}else{
     echo "La connexion n'a pu être établie.<br />";
     die( print_r( sqlsrv_errors(), true));
}

// Requête pour récupérer les derniers produits en stock
$sql = "SELECT top 5 m.MVT_CRDA,m.MVT_CRHE,m.ART_CODE,m.STK_NOSU,a.ART_DESL, a.ART_ALPHA9, a.ART_CCLI
        FROM MVT_DAT m 
        INNER JOIN ART_PAR a ON a.ART_CODE=m.ART_CODE 
        WHERE m.TMV_CODE='10010' AND m.STK_LIEU='RECPA01' 
        ORDER BY m.MVT_CRDA DESC;" ; 


$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}

echo ("<br><strong><H1><center>CHAINE AUTO : DERNIERS PSID ENTREES EN STOCK 5RECPA01)</center></H1></strong></br>");
echo( "<table class=\"tableau tablesorter tablesearch\" border=\"1\" cellpadding=\"0\" cellspacing=\"1\" style=\"margin-left: auto; border-collapse:collapse; margin-right: auto; width:65%\">\n" );
echo( "<tr>
<td><strong><div align=\"center\">DATE</div></strong></td>
<td><strong><div align=\"center\">HEURE</div></strong></td>
<td><strong><div align=\"center\">PSID</div></strong></td>
<td><strong><div align=\"center\">SP</div></strong></td>
<td><strong><div align=\"center\">DESIGNATION</div></strong></td>
<td><strong><div align=\"center\">IMPRIMER</div></strong></td>
</tr>" );

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_BOTH) ) {
    $d = $row['0'];
    $H = $row['1'];
    $PSID = $row['2'];
    $SP = $row['3'];
    $DESI = $row['4'];
    $ART_ALPHA9 = $row['5'];
    $ART_CCLI = $row['6'];
    
    // Requête pour récupérer le jour de l'année par rapport à la date d'entrée en stock
    $sql1 = "SELECT MAX(DATENAME(y, MVT_CRDA)) AS NUM FROM MVT_DAT WHERE STK_NoSU = '".$SP."' AND TMV_CODE IN ('10010', '10030')";
    $stmt1 = sqlsrv_query($conn, $sql1);
    if ($stmt1 === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
    $NUM = $row1['NUM'] ; 

echo( "<tr>
<td><div align=\"center\">".date_format($d, 'd-m-Y')."</div></td>
<td><div align=\"center\">".$H."</div></td>
<td><div align=\"center\">".$PSID."</div></td>
<td><div align=\"center\">".$SP."</div></td>
<td><div align=\"center\">".$DESI."</div></td>
<!-- Ajout de l'image d'impression avec événement onClick -->
<td><div align=\"center\">
<img src='/images/printer.png' class='printer-img' style='cursor: pointer;' onclick=\"submitForm('$SP', '$ART_ALPHA9', '$PSID', '$DESI', '$ART_CCLI', '$NUM')\" alt='Imprimer'>
</div></td>

</tr>" );
}

echo( "</table>\n" );
if (isset($_POST['SP'])) {
    $SP = $_POST['SP'];
    $ART_ALPHA9 = $_POST['ART_ALPHA9'];
    $ART_CODE = $_POST['ART_CODE'];
    $ART_DESL = $_POST['ART_DESL'];
    $ART_CCLI = $_POST['ART_CCLI'];
    $NUM = $_POST['NUM'];

    // Détails pour l'impression
    $printer_ip = $ipprinter;  // Remplacez par l'adresse IP de l'imprimante dans $ipprinter en haut
    $port = xxxx;  // Remplacez par le port de l'imprimante pour autoriser la connexion

    // Connexion au socket de l'imprimante
    $sock = socket_create(AF_INET, SOCK_STREAM, 0);
    if (!$sock) {
        die("Erreur : Impossible de creer le socket");
    }

    // Ouverture du socket vers l'imprimante
    if (!socket_connect($sock, $printer_ip, $port)) {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        die("Impossible de se connecter : [$errorcode] $errormsg\n");
    }

    // Envoi des données à l'imprimante (exemple de ZPL)
    $zpl = 
    "^XA".
    "^PW832".
    "^LH0,0^FS".
    "^POI".
    "^CF0,36".
    "^FO440,000^A0N,115,130^FH^FD$ART_ALPHA9^FS".
    "^FO410,100^ADN,18,10^FH^FD$ART_CODE^FS".
    "^FO410,120^ABN,11,07^FH^FD$ART_DESL^FS".
    "^FO720,000^A0R,60,40^FH^FD$ART_CCLI^FS".    
    "^FO410,140".
    "^BY2".
    "^BCN,090,Y,N".
    "^FD$SP^FS".
    "^FO740,125^AVN,30,36^FH^FD^FS".
    "^FO740,190^AVN,30,36^FH^FD^FS".
    "^FO690,235^ADN,18,10^FH^FD$NUM^FS".
    "^RS,,,3,N".
    "^RR3,1".
    "^RFW,A,2,,A^FD$SP^FS".
    "^XZ";

    // Envoi des données
    $out = strlen($zpl);
    socket_send($sock, $zpl, $out, 0);
    socket_close($sock);  // Fermeture de la connexion socket

    // Affichage d'un message de succès et redirection en JavaScript
    echo "<script>alert('Impression reussie pour le SP : $SP'); window.location.href = window.location.href;</script>";
}

sqlsrv_free_stmt( $stmt);
sqlsrv_free_stmt( $stmt1);
sqlsrv_close( $conn); 

?>
</body>
</html>