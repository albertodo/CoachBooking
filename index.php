<?php
session_start();
$_SESSION= array();
session_destroy();

if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off'){
	//Richiesta su https
}
else {
	$redirect='https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved permanently');
	header('Location: '.$redirect);
	exit();
}

    setcookie("cookie_test", "cookie_value", time()+3600);
    function php_cookie_enable()
    {
        if (@$_COOKIE["cookie_test"] == "cookie_value")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    if (php_cookie_enable()==false)
    {
        echo "Attenzione hai i cookie disabilitati!<BR><BR>Per proseguire attivali e ricarica la pagina.";
        die();
    }

?>
<html>
<head><title>Home page</title>

<style>
div#reglog{
	position: absolute;
	left:10;
	top:10;
	text-align: center;
}


</style>

</head>
<script type="text/javascript">


function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


function verifica(){
	flaga = 0;
	flagb = 0;
	flagu = 0;
	indice = 0;

 	//Controllo email
 	usr = document.getElementById('email').value;
 	for (i=0;i<usr.length;i++){
		test = usr.charAt(i);
		if (test == '@'){
				flagu = 1;
				break;
			}
	}

	//controllo minuscole
	psw = document.getElementById('password').value;
	
	for (i=0;i<psw.length;i++){
		test = psw.charAt(i);
		if (test == test.toLowerCase() && !isNumeric(test)) {
			flaga=1;
			indice = i;
			break;
		}
	}

	//controllo maiuscola o numero
		for (i=0;i<psw.length;i++){
			test = psw.charAt(i);
			if(i != indice && (isNumeric(test) ||  test == test.toUpperCase())){
				flagb=1;
				break;
			}
		}
	

	
	if (flagu==0 || flaga==0 || flagb==0) 
	{
		document.write("Errore nell'inserimento dei dati! <BR><BR><a href=\"index.php\">Torna indietro <BR></a><BR>");
	}
}


function infoU(){
	alert("Lo username deve essere un indirizzo email valido");
}
function infoP(){
	alert("La password deve contenere almeno una minuscola e o un numero o una lettera maiuscola");
}

</script>
<noscript><section><center><p style="color:red;">JavaScript non e' abilitato. Il sito potrebbe non funzionare correttamente</p></section></noscript>
<body>
	<H1><center><strong>SITO MINIBUS</strong></center></H1>
		<div id="reglog">
			<a href="index.php">Home page <BR></a><BR>
			<form action="registrati.php" METHOD=post>
				<center>Login/Registrati:</center><BR>
				Email:<input type="email" required  id="email" name=email>
				<input type="button" onclick="infoU()" value='?'><BR>
				Password:<input type="password" required id="password" name=password> <!-- Far apparire descrizione onmouseover -->
				<input type="button" onclick="infoP()" value='?'><BR>
				<!--<input type=submit value=Registrati onclick="verifica()"> -->
				<BR>
				<input type=submit value=Registrati/Login onclick="verifica()">
				<input type=reset value=Azzera><BR>
			</form>

		</div>


		<section><center>
		<?php
				$conn = mysqli_connect('localhost','root', '','esame');
					if (!$conn) {
						echo("Errore nella connessione(".mysqli_connect_errno().")" .mysqli_connect_error()); //Poi da togliere
						die();
					}
				
				$query = "SELECT partenza,arrivo, sum(passeggeri) AS prenotati FROM viaggi GROUP BY partenza,arrivo ORDER BY partenza"; 
				if($ris = mysqli_query($conn, $query)){ 
					if(mysqli_num_rows($ris) == 0){
						echo "<H2><BR>Nessun viaggio presente</h2>";
						die();
					}
					else{
					echo "<TABLE BORDER=0><CAPTION><EM>Viaggi minibus programmati.<BR></EM></CAPTION><BR><TR><TD><strong>Partenza</strong><TD><strong>Arrivo</strong><TD><strong>Num passeggeri</strong>";
					for ($i=0; $i<mysqli_num_rows($ris); $i++){
						$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
						if($i==0){//prima riga
							echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD></TR>";
							$partprec=$riga[0];
							$arrprec=$riga[1];
							
						}
						else if($riga[0]!=$arrprec){
							echo "<TR><TD><center>".$arrprec."</center></TD><TD><center>".$riga[0]."</center></TD><TD><center>vuoto</center></TD></TR>";
							echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD></TR>";
							$partprec=$riga[0];
							$arrprec=$riga[1];
						}
						else {
							echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD></TR>";
							$partprec=$riga[0];
							$arrprec=$riga[1];
						}
				}
					echo "</TABLE>";
				}
				}
				
				

 				mysqli_close($conn);
			?> 
		</center></section>

	
</body>


</html>