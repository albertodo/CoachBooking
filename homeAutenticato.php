<?php
session_start();
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off'){
	//Richiesta su https
}
else {
	$redirect='https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved permanently');
	header('Location: '.$redirect);
	exit();
}
if(!isset($_SESSION['email'])){
	echo "Utente non autenticato.<BR><BR>Vai a: <a href=\"index.php\">Home page<BR></a><BR>";
		die();
}

$t=time(); 
$diff=0; 
  //Ogni volta che carica questa pagina devo aggiornare il cookie a 2 min
if (isset($_SESSION['time'])){
	$t0=$_SESSION['time']; 
	$diff=($t-$t0); // inactivity 
}
if ($diff > 120) { //inactivity period too long
	$_SESSION = array();
	session_destroy();
	echo "Sessione scaduta!<BR><BR>Torna indietro: <a href=\"index.php\">Home page<BR></a><BR>";
	die();
} 
else {
	$_SESSION['time']=time();
}
?>
<html>
<head><title>Home page</title></head>

<body>
	<H1><center><strong>SITO MINIBUS</strong></center></H1>
		<section style="position:absolute;top:10;left:0;text-align:center;">
			<a href="homeAutenticato.php">Home page personale<BR></a><BR>
			    <?php echo "Welcome ".$_SESSION['email']."!"; 
			    $conn = mysqli_connect('localhost','root', '','esame');
			    $usr=$_SESSION['email'];
			    $query = "SELECT * FROM utenti WHERE email='$usr' AND richiesto='si'";
			    $ris = mysqli_query($conn, $query);
			    if(mysqli_num_rows($ris) == 0){ ?>  <!-- non va -->
			    <form action="inseritoNuovoViaggioC.php" METHOD=post>
				<center><BR><BR>Inserisci partenza e arrivo:</center><BR>
				Partenza:<input type="text" id="partenza"  name=partenza>
				<SELECT style="text-align:center" NAME=partenzaEsistente id="partenzaEsistente" SIZE=1>
					<OPTION value=altro selected>altro
					<?php  $conn = mysqli_connect('localhost','root', '','esame');

						$query = "SELECT distinct partenza FROM viaggi ORDER BY partenza";
						if($ris = mysqli_query($conn, $query)){ 
				
							for ($i=0; $i<mysqli_num_rows($ris); $i++){
								$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
								echo "<OPTION value=$riga[0]>".$riga[0];
							}
						} 
						mysqli_close($conn);
 					?>
 				</SELECT><BR> 
 				
 				Arrivo:<input type="text" id="arrivo" name=arrivo>

				<SELECT style="text-align:center" NAME=arrivoEsistente id="arrivoEsistente" SIZE=1>
					<OPTION value=altro selected>altro
					<?php  $conn = mysqli_connect('localhost','root', '','esame');

						$query = "SELECT distinct arrivo FROM viaggi ORDER BY arrivo";
						if($ris = mysqli_query($conn, $query)){ 
				
							for ($i=0; $i<mysqli_num_rows($ris); $i++){
								$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
								echo "<OPTION value=$riga[0]>".$riga[0];
							}
						} 
						mysqli_close($conn);
 					?>
				</SELECT><BR> 

				Num passeggeri: <input type="text" placeholder="1-4" id="numPasseggeri" name=numPasseggeri><BR>
				<input type=submit value=Aggiungi viaggio> 
				<input type=reset value=Azzera><BR>
			</form>   
			<BR><BR><center>
			<form action="index.php" method=post>
				<input type=submit value=Logout> 
			</center></form>
			<?php } else {
				echo "<BR><BR>Richiesta viaggio gia inserita<BR><BR>"; 
				echo"<form action=\"cancella.php\" method=post>
				<input type=submit value=\"Cancella prenotazione\"> 
				</form>";
				echo"<center><form action=\"index.php\" method=post>
				<input type=submit value=Logout> 
				</center></form>";
				

			}?>
			 	
			

		</section>

		<section><center>

		<?php
				$conn = mysqli_connect('localhost','root', '','esame');
					if (!$conn) {
						echo("Errore nella connessione(".mysqli_connect_errno().")" .mysqli_connect_error()); //Poi da togliere
						echo("<BR><BR>Ricarica: <a href=\"homeAutenticato.php\">Home page personale<BR></a><BR>");
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
				echo "</TABLE>";
				echo "<BR><BR><BR>";
				echo "<TABLE BORDER=0><CAPTION><EM>Viaggi minibus programmati in dettaglio</EM></CAPTION><BR><TR><TD><strong>Partenza</strong><TD><strong>Arrivo</strong><TD><strong>Utente</strong><TD><strong>Num passeggeri</strong></TD></TR>";
				$query = "SELECT * FROM viaggi ORDER BY partenza"; 
				if($ris = mysqli_query($conn, $query)){ 
				
					for ($i=0; $i<mysqli_num_rows($ris); $i++){
						$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
						if($i==0){//prima riga
							if($riga[2]==$_SESSION['email']){
								echo "<TR><TD style=\"color:red;\"><center>".$riga[0]."</center></TD><TD style=\"color:red;\"><center>".$riga[1]."</center></TD><TD style=\"color:red;\"><center>".$riga[2]."</center></TD><TD style=\"color:red;\"><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}
							else { 
								echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD><TD><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}
							
						}
						else if($riga[0]==$partprec && $riga[1]==$arrprec){
							if($riga[2]==$_SESSION['email']){
								echo "<TR><TD style=\"color:red;\"><center>".$riga[0]."</center></TD><TD style=\"color:red;\"><center>".$riga[1]."</center></TD><TD style=\"color:red;\"><center>".$riga[2]."</center></TD><TD style=\"color:red;\"><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}
							else { 
								echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD><TD><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}

						}
						else if($riga[0]!=$arrprec) {
							//inserisco tratta vuota
							echo "<TR><TD><center>".$arrprec."</center></TD><TD><center>".$riga[0]."</center></TD><TD><center>nessuno</center></TD><TD><center>vuoto</center></TD></TR>";
							if($riga[2]==$_SESSION['email']){
								echo "<TR><TD style=\"color:red;\"><center>".$riga[0]."</center></TD><TD style=\"color:red;\"><center>".$riga[1]."</center></TD><TD style=\"color:red;\"><center>".$riga[2]."</center></TD><TD style=\"color:red;\"><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}
							else { 
								echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD><TD><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}

						}
						else {
							if($riga[2]==$_SESSION['email']){
								echo "<TR><TD style=\"color:red;\"><center>".$riga[0]."</center></TD><TD style=\"color:red;\"><center>".$riga[1]."</center></TD><TD style=\"color:red;\"><center>".$riga[2]."</center></TD><TD style=\"color:red;\"><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}
							else { 
								echo "<TR><TD><center>".$riga[0]."</center></TD><TD><center>".$riga[1]."</center></TD><TD><center>".$riga[2]."</center></TD><TD><center>".$riga[3]."</center></TD></TR>";
								$partprec=$riga[0];
								$arrprec=$riga[1];
							}
						}
						
					}
				}
				echo "</TABLE>";

 				mysqli_close($conn);
			?> 
		</center></section>

	
</body>


</html>