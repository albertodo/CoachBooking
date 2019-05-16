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

$conn = mysqli_connect('localhost','root', '','esame');

if(isset($_SESSION['email'])){
	$email=$_SESSION['email'];
	$query = "SELECT  * FROM utenti WHERE email = '$email' AND richiesto='si'";
	$ris = mysqli_query($conn, $query);
	if(mysqli_num_rows($ris)>0){
		echo "Bad request.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page<BR></a><BR>";
		die();
	}

}
else {
	echo "Bad request.<BR><BR>Torna indietro: <a href=\"index.php\">Home page<BR></a><BR>";
	die();
}
$t=time(); 
$diff=0; 
  //Ogni volta che carica questa pagina devo aggiornare il cookie a 2 min
if (isset($_SESSION['time'])){
	$t0=$_SESSION['time']; 
	$diff=($t-$t0); // inactivity 
}
if ($diff > 120) { // inactivity period too long
	$_SESSION = array();
	session_destroy();
	echo "Sessione scaduta!<BR><BR>Torna indietro: <a href=\"index.php\">Home page<BR></a><BR>";
	die();
} 
else {
	$_SESSION['time']=time();
}

$cap = 4;
$flag = 0;
$cont = 0;
$conta = 0;
$contb = 0;
$contc=0;
$contd=0;


// $_REQUEST['partenza'] = strtoupper($_REQUEST['partenza']);
// $_REQUEST['partenzaEsistente'] = strtoupper($_REQUEST['partenzaEsistente']);
// $_REQUEST['arrivo'] = strtoupper($_REQUEST['arrivo']);
// $_REQUEST['arrivoEsistente'] = strtoupper($_REQUEST['arrivoEsistente']);




if(!isset($_REQUEST['partenza']) || !isset($_REQUEST['partenzaEsistente']) || !isset($_REQUEST['arrivo']) || !isset($_REQUEST['arrivoEsistente'])){
	echo "Bad request<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
if(is_numeric($_REQUEST['partenza'])||is_numeric($_REQUEST['partenzaEsistente'])||is_numeric($_REQUEST['arrivo'])||is_numeric($_REQUEST['arrivoEsistente'])){
	echo "Non e' possibile inserire stazioni numeriche<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
if($_REQUEST['partenzaEsistente']!="" && $_REQUEST['partenzaEsistente'] != "altro"){
	// if($_REQUEST['partenzaEsistente'] <'A' || $_REQUEST['partenzaEsistente'] >'Z'){
	// 	echo "Partenza e arrivo devono essere compesi tra A e Z.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	// 	die();
	//}
	$partenza = $_REQUEST['partenzaEsistente'];
	
}
	

else if ($_REQUEST['partenza']!="" && ($_REQUEST['partenzaEsistente']=="" || $_REQUEST['partenzaEsistente']=="altro")){
	// if($_REQUEST['partenza'] <'A' || $_REQUEST['partenza'] >'Z'){
	// 	echo "Partenza e arrivo devono essere compesi tra A e Z.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	// 	die();
	//}
	$partenza = $_REQUEST['partenza'];
}

else if ($_REQUEST['partenza']=="" && $_REQUEST['partenzaEsistente']==""){
	echo "Partenza non valida.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
	

else if($_REQUEST['partenzaEsistente'] == "altro"){
	echo "Partenza non valida.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
else {
	echo "Partenza non valida.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
if($_REQUEST['arrivoEsistente']!="" && $_REQUEST['arrivoEsistente'] != "altro"){
	// if($_REQUEST['arrivoEsistente'] <'A' || $_REQUEST['arrivoEsistente'] >'Z'){
	// 	echo "Partenza e arrivo devono essere compesi tra A e Z.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	// 	die();
	// }
	$arrivo = $_REQUEST['arrivoEsistente'];
}

else if ($_REQUEST['arrivo']!="" && ($_REQUEST['arrivoEsistente']=="" || $_REQUEST['arrivoEsistente']=="altro")){
	// if($_REQUEST['arrivo'] <'A' || $_REQUEST['arrivo'] >'Z'){
	// 	echo "Partenza e arrivo devono essere compesi tra A e Z.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	// 	die();
	// }
	$arrivo = $_REQUEST['arrivo'];
}
	

else if ($_REQUEST['arrivo']=="" && $_REQUEST['arrivoEsistente']==""){
	echo "Arrivo non valido.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
	

else if($_REQUEST['arrivoEsistente'] == "altro"){
	echo "Arrivo non valido.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
else {
	echo "Arrivo non valido.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}

if($_REQUEST['numPasseggeri'] == "" || !isset($_REQUEST['numPasseggeri']) || !is_numeric($_REQUEST['numPasseggeri']) || $_REQUEST['numPasseggeri']<=0 || $_REQUEST['numPasseggeri']>$cap){
	echo "Num passeggeri non valido. Capienza max minibus: ".$cap."<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
else $nump = $_REQUEST['numPasseggeri'];

if($partenza >= $arrivo){
	echo "Errore: partenza e' dopo arrivo.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();
}
	$partenza=mysqli_real_escape_string($conn, $partenza);
	$arrivo=mysqli_real_escape_string($conn, $arrivo);

	

try{
	mysqli_autocommit($conn,false);

	$query = "SELECT  * FROM viaggi FOR UPDATE";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comando fallito");
	//$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
	if(mysqli_num_rows($ris)==0){
		//Inserisco perche tabella vuota
		$query = "INSERT INTO viaggi VALUES('$partenza', '$arrivo', '$email', '$nump')";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
		
		$riga = @mysqli_fetch_array($ris, MYSQLI_NUM);
		echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
		$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
		mysqli_commit($conn);
		mysqli_autocommit($conn,true);
		mysqli_close($conn);
		$_SESSION['flag']=1;
		die();
	}
	//Fine caso tabella vuota


	//Inizio tratta è gia esistente
	$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi WHERE partenza = '$partenza' AND arrivo = '$arrivo' GROUP BY partenza,arrivo FOR UPDATE";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comando fallito");
		
		$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
		if(mysqli_num_rows($ris)>0) { //se esiste la tratta
			if ($riga[2]+$nump <= $cap) {
			//echo $riga[0]; echo $riga[1]; echo $riga[2]; echo $nump;
			//posso inserire
			$query = "INSERT INTO viaggi VALUES('$partenza', '$arrivo', '$email', '$nump')";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					
					$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
					mysqli_commit($conn);
					mysqli_autocommit($conn,true);
					mysqli_close($conn);
					echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
					$_SESSION['flag']=1;
					die();
				
			}
				else {
					mysqli_autocommit($conn,true);
					mysqli_close($conn);
					echo "Posti esauriti nella tratta di partenza.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
					die();
				}
		}
		// else {
		// 	echo "Non è una tratta esistente.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
		// }
	//Fine tratta gia esistente


	//Inizio caso sottotratta
	$query = "SELECT distinct partenza,arrivo, sum(passeggeri) FROM viaggi WHERE partenza < '$partenza' AND arrivo > '$arrivo' GROUP BY partenza,arrivo FOR UPDATE";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comano fallito");
		
		$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
			if ((mysqli_num_rows($ris)>0) && ($riga[2]+$nump) <= $cap) {

				//posso inserire 
				//mi salvo utenti e passeggeri che ci sono nella tratta piu grossa. 
				$partenzaTrattaGrossa = $riga[0];
				$arrivoTrattaGrossa = $riga[1];

				
				//NSERIRE NELLA TABELLA I SOTTOCASI
				
				$query = "SELECT utente,passeggeri FROM viaggi WHERE partenza = '$partenzaTrattaGrossa' AND arrivo = '$arrivoTrattaGrossa'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					for ($i=0; $i< mysqli_num_rows($ris); $i++){
						$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
						$users[$i]=$riga[0];
						$pax[$i]=$riga[1];
					}
					$users[$i]=$email;
					$pax[$i]=$nump;
					$finei = $i;
					//ora elimino la tratta grossa 
				$query = "DELETE FROM `viaggi` WHERE partenza='$partenzaTrattaGrossa' AND arrivo='$arrivoTrattaGrossa'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
				
				//ora inserisco le sottotratte
				//partenzaTrattaGrossa --> partenza --> arrivo --> arrivoTrattaGrossa
				//per tutti gli utenti e i passeggeri (compreso quello nuovo)
				for ($i=0;$i<$finei;$i++){
					$u = $users[$i];
					$p = $pax[$i];
					$query = "INSERT INTO viaggi VALUES('$partenzaTrattaGrossa', '$partenza', '$u', '$p')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
						
				}
				for ($i=0;$i<$finei;$i++){
					$u = $users[$i];
					$p = $pax[$i];
					$query = "INSERT INTO viaggi VALUES('$partenza', '$arrivo', '$u', '$p')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
						
				}
				$query = "INSERT INTO viaggi VALUES('$partenza', '$arrivo', '$email', '$nump')";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					

				for ($i=0;$i<$finei;$i++){
					$u = $users[$i];
					$p = $pax[$i];
					$query = "INSERT INTO viaggi VALUES('$arrivo', '$arrivoTrattaGrossa', '$u', '$p')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
						
				}
				$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
				$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
				echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
				mysqli_commit($conn);
				mysqli_autocommit($conn,true);
				mysqli_close($conn);
				$_SESSION['flag']=1;
				die();
			
				}
				
			// else {
			// 	echo "Non è una sottotratta.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			// }
		//Fine caso sottotratta


		//Inizio caso in cui parte da fuori (prima) e finisce fuori (dopo)
		$query = "SELECT  MIN(partenza) FROM viaggi FOR UPDATE";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");

			$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
			$partenzaMin=$riga[0];
		
		$query = "SELECT  MAX(arrivo) FROM viaggi ";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
			$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
			$arrivoMax=$riga[0];
		
		$no1=0;
		$no=1;
		if($partenza < $partenzaMin && $arrivo > $arrivoMax){
			$no1=1;
			//controllo se ci sto nelle tratte intermedie 
			$query = "SELECT  partenza,arrivo,sum(passeggeri) from viaggi group by partenza,arrivo";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito"); 
				$index = mysqli_num_rows($ris);
				for ($i=0; $i<mysqli_num_rows($ris); $i++){
					$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
					if($riga[2]+$nump > $cap){
						$no = 1;
						break;
					}
					else {
						$no=0;
						$pp[$i]=$riga[0];
						$aa[$i]=$riga[1];
					}
				}
			

		}

		if ($no == 0 && $no1==1){
			
			//inserisco in tutte le tratte tranne prima e ultima a mano 
			for ($i=0; $i<$index; $i++){
				$p=$pp[$i];
				$a=$aa[$i];
				
				$query = "INSERT INTO  viaggi VALUES('$p','$a','$email','$nump')";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
			}
			//inserisco prima e ultima a mano
			
			$query = "INSERT INTO  viaggi VALUES('$partenza','$partenzaMin','$email','$nump')";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			$query = "INSERT INTO  viaggi VALUES('$arrivoMax','$arrivo','$email','$nump')";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			mysqli_commit($conn);
			mysqli_autocommit($conn,true);
			mysqli_close($conn);
			$_SESSION['flag']=1;
			echo "<BR><BR>Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			die();
		}
		// else {
		// 	echo "<BR><BR>Impossibile inserire nuovo viaggio3333<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
		// }
		//Fine caso in cui parte da fuori (prima) e finisce fuori (dopo)



		//Inizio caso stazioni intermedie con partenza e arrivo esistenti
		$query = "SELECT  partenza,sum(passeggeri) FROM viaggi WHERE partenza = '$partenza' group by partenza FOR UPDATE";
		if(!($ris = mysqli_query($conn, $query))) 
			throw new Exception("Comando fallito");
			if(mysqli_num_rows($ris)){//se esiste stazione di partenza
					$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
					$spart = $riga[0];
					
					if(($riga[1] + $nump) > $cap){
						//echo "<BR><BR>Non ci sto nella partenza<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
						//die();
						
					}
					//Se esiste la partenza e ci sto allora cerco la stazione di arrivo
					$query = "SELECT  arrivo,sum(passeggeri) FROM viaggi WHERE arrivo = '$arrivo' group by arrivo";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
						if(mysqli_num_rows($ris)){//se esiste stazione di arrivo
							$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
							$sarr = $riga[0];
							
							if(($riga[1] + $nump) > $cap){
								//echo "<BR><BR>Non ci sto nell'arrivo<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
								//die();
								
							}
							else{
								//se esiste la stazione di arrivo e ci sto
								//controllo se ci sto nelle sottotratte
								$flagno=0;
								$query = "SELECT sum(passeggeri) FROM viaggi WHERE partenza >= '$partenza' AND arrivo <= '$arrivo' group by partenza,arrivo";
								if(!($ris = mysqli_query($conn, $query)))
									throw new Exception("Comando fallito");
									for ($i=0;$i<mysqli_num_rows($ris);$i++){
										$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
										
										if(($riga[0]+$nump) > $cap){
											echo "Non ci sto in una tratta intermedia<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
											$flagno = 1;
										}									
									}
									

								

								if($flagno==0){
									//posso inserire anche nelle sottotratte
									$query = "SELECT  partenza,arrivo FROM viaggi WHERE partenza >= '$partenza' AND arrivo <= '$arrivo' group by partenza,arrivo";
									if(!($ris = mysqli_query($conn, $query)))
										throw new Exception("Comando fallito");
										$cont = mysqli_num_rows($ris);
										for ($i=0;$i<$cont;$i++){
												if($riga = mysqli_fetch_array($ris, MYSQLI_NUM)){
													if(($i!= 0) &&($p != $riga[0]) && ($a != $riga[1])){
														$p=$riga[0];
														$a=$riga[1];
														
														$query = "INSERT INTO viaggi VALUES('$p', '$a', '$email', '$nump')";
														if(!($ris1 = mysqli_query($conn, $query)))
															throw new Exception("Comando fallito");
													}
													else if($i==0){
														$p=$riga[0];
														$a=$riga[1];
														
														$query = "INSERT INTO viaggi VALUES('$p', '$a', '$email', '$nump')";
														if(!($ris1 = mysqli_query($conn, $query)))
															throw new Exception("Comando fallito");
													}
											}
										}
										echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
										$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
										if(!($ris = mysqli_query($conn, $query)))
											throw new Exception("Comando fallito");
										mysqli_commit($conn);
										mysqli_autocommit($conn,true);
										mysqli_close($conn);
										$_SESSION['flag']=1;
										die();
									
								}
							}
					}
					 
			
		}	
	//Fine caso stazioni intermedie con partenza e arrivo esistenti


//Inizio caso parte da fuori e finisce in una stazione/tratta esistente 
	$l=0;
	$query = "SELECT  MIN(partenza) FROM viaggi for update";
	if(!($ris = mysqli_query($conn, $query))) 
		throw new Exception("Comando fallito");
		if(mysqli_num_rows($ris) > 0){
			$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
			$partenzaMin=$riga[0];
			$l=1;
		}
	echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	if ($l==1 && $partenzaMin>$partenza){
		//guardo se finisco prima
		if($arrivo < $partenzaMin){
			//inserisco l'utente prima e basta
			$query = "INSERT INTO  viaggi VALUES('$partenza','$arrivo','$email','$nump')";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");

			// $query = "INSERT INTO  viaggi VALUES('$arrivo','$partenzaMin','','0')";
			// if(!($ris = mysqli_query($conn, $query)))
			// 	throw new Exception("Comando fallito");
			$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			mysqli_commit($conn);
			mysqli_autocommit($conn,true);
			mysqli_close($conn);
			$_SESSION['flag']=1;
			
			die();
		}

		//controllo se arrivo in una tratta esistente
		//////FORSE
		//controllo se ci sto nelle sottotratte
		$flagaa=0;
		$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi where arrivo <= '$arrivo' group by partenza,arrivo";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
			for ($i=0; $i<mysqli_num_rows($ris); $i++){
				$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
				if(($riga[2] + $nump) > $cap){
					$flagaa=1; //se non ci sto modifico il flagaa
					break;
				}
				else {
					$stp=$riga[0];
					$sta=$riga[1];
				}
			}
			$indice=mysqli_num_rows($ris);
			if($flagaa == 0){//vuol dire che ci sto in tutte le sottotratte e posso aggiungere
				for ($i=0; $i<$indice; $i++){
					$p=$stp[$i];
					$a=$sta[$i];
					$query = "INSERT INTO  viaggi VALUES('$p','$a','$email','$nump')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");

				}
				//aggiungo anche la tratta iniziale
				$query = "INSERT INTO  viaggi VALUES('$partenza','$partenzaMin','$email','$nump')";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
				
				//Se finisco a meta smonto e rimonto
				$ff=0;
				$query = "SELECT * from viaggi where partenza <'$arrivo' AND arrivo >'$arrivo'";
				if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito"); 
					if(mysqli_num_rows($ris) > 0){//allora finisco a meta
						$ind=mysqli_num_rows($ris);
						for ($i=0; $i<$ind; $i++){
							$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
							$p=$riga[0];
							$a=$riga[1];
							$u[$i]=$riga[2];
							$pas[$i]=$riga[3];
							$ff=1;
						}
							

					}
					//delete vecchia tratta
					$query = "DELETE FROM viaggi where partenza <'$arrivo' AND arrivo >'$arrivo'";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");

					//ricostruisco sottotratta
					if($ff==1){
						for ($i=0; $i<$ind; $i++){

							$utente=$u[$i];
							$pax=$pas[$i];
							$query = "INSERT INTO  viaggi VALUES('$p','$arrivo','$utente','$pax')";
							if(!($ris = mysqli_query($conn, $query)))
								throw new Exception("Comando fallito");
							$query = "INSERT INTO  viaggi VALUES('$arrivo','$a','$utente','$pax')";
							if(!($ris = mysqli_query($conn, $query)))
								throw new Exception("Comando fallito");
							
						}
						//aggiungo nuovo utente.
						$query = "INSERT INTO  viaggi VALUES('$p','$arrivo','$email','$nump')";
						if(!($ris = mysqli_query($conn, $query)))
							throw new Exception("Comando fallito");

					}
				


				$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
				if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
				mysqli_commit($conn);
				mysqli_autocommit($conn,true);
				mysqli_close($conn);
				$_SESSION['flag']=1;
				echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
				die();
			}
			// else {
			// 	echo "Non ci sto in qualche sottotratta<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			// }
	}
	//Fine caso parto da fuori e finisco in una stazione/tratta esistente



	//Inizio caso inizio da una tratta/stazione esistente
	//Controllo di finire fuori da tutto
	$query = "SELECT  MAX(arrivo) FROM viaggi for update";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comando fallito");
		$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
		if($arrivo > $riga[0]){//2
			//sto finendo fuori da tutto.
			//Controllo se parto da una stazione o da una tratta
			
			$arrivoMax=$riga[0];
			$query = "SELECT  partenza,arrivo FROM viaggi";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
				$indice=mysqli_num_rows($ris);
				for ($i=0; $i<$indice; $i++){//for esterno
					$riga1 = mysqli_fetch_array($ris, MYSQLI_NUM);
					if($riga1[0]==$partenza){
						//Sto partendo da una stazione esistente.//Inserisco per ogni sottotratta piu l'ultima e poi die()
						//controllo se ci sto nelle sottotratte
						
						$flagbb=0;
						$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi where partenza >= '$partenza' group by partenza,arrivo";
						if(!($ris1 = mysqli_query($conn, $query)))
							throw new Exception("Comando fallito");
							for ($i=0; $i<mysqli_num_rows($ris1); $i++){
								$riga2 = mysqli_fetch_array($ris1, MYSQLI_NUM);
								if(($riga2[2]+$nump) > $cap) 
									$flagbb=1; 
								else {
									$p[$i]=$riga2[0];
									$a[$i]=$riga2[1];
								}
							}
							$ind=$i;
							if($flagbb==0){
								//Ci sto in tutte le sottotratte
								//Inserisco per tutte le sottotratte piu l'ultima
								for ($i=0; $i<$ind; $i++){
									$pp=$p[$i];
									$aa=$a[$i];
									$query = "INSERT INTO  viaggi VALUES('$pp','$aa','$email','$nump')";
									if(!($ris2 = mysqli_query($conn, $query)))
										throw new Exception("Comando fallito");
								}
								$query = "INSERT INTO  viaggi VALUES('$arrivoMax','$arrivo','$email','$nump')";
								if(!($ris2 = mysqli_query($conn, $query)))
									throw new Exception("Comando fallito");
								$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
								if(!($ris2 = mysqli_query($conn, $query)))
									throw new Exception("Comando fallito");
								$_SESSION['flag']=1;
								mysqli_commit($conn);
								mysqli_autocommit($conn,true);
								mysqli_close($conn);
								echo ("Inserito 2222");
								die();
							}
						
					}
					else if($partenza > $riga1[0] && $partenza < $riga1[1]){
						//Sto partendo da una tratta esistente
						//Controllo se ci sto nella tratta di partenza
						
						$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi where partenza >'$partenza' and arrivo < '$arrivo' group by partenza,arrivo";
						if(!($ris11 = mysqli_query($conn, $query)))
							throw new Exception("Comando fallito");
							$riga3 = mysqli_fetch_array($ris11, MYSQLI_NUM);
							$partenzaa=$riga3[0];
							$arrivoo=$riga3[1];
							if(($riga3[2]+$nump) > $cap){
								//Non ci sto nella tratta di partenza
								echo "Posti esauriti nella tratta di partenza<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
								break;
							}
							else {
								//Controllo tutte le sottotratte e poi smonto tratta di partenza
								$flagcc=0;
								$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi where partenza > '$partenza' group by partenza,arrivo";
								if(!($ris1 = mysqli_query($conn, $query)))
									throw new Exception("Comando fallito");
									for ($i=0; $i<mysqli_num_rows($ris1); $i++){
										$riga = mysqli_fetch_array($ris1, MYSQLI_NUM);
										if(($riga[2]+$nump) > $cap) {
											$flagcc=1; 
											break;
										}
										else {
											$p[$i]=$riga[0];
											$a[$i]=$riga[1];
										}
									}
									$ind=$i;
									if($flagcc==0){
										
										//Ci sto in tutte le sottotratte
										//Inserisco per tutte le sottotratte piu l'ultima
										for ($i=0; $i<$ind; $i++){
											$pp=$p[$i];
											$aa=$a[$i];
											
											$query = "INSERT INTO  viaggi VALUES('$pp','$aa','$email','$nump')";
											if(!($ris2 = mysqli_query($conn, $query)))
												throw new Exception("Comando fallito");
										}
										
										$query = "INSERT INTO  viaggi VALUES('$arrivoMax','$arrivo','$email','$nump')";
										if(!($ris2 = mysqli_query($conn, $query)))
											throw new Exception("Comando fallito");
										$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
										if(!($ris2 = mysqli_query($conn, $query)))
											throw new Exception("Comando fallito");

									}
									//Qua smonto tratta di partenza
									/////////////////////////////////////
									
									
									$query = "SELECT  * FROM viaggi where partenza < '$partenza' and arrivo > '$partenza'";
									if(!($ris1 = mysqli_query($conn, $query)))
										throw new Exception("Comando fallito");
									$inq=mysqli_num_rows($ris1);
									for ($i=0; $i<$inq; $i++){
										$riga = mysqli_fetch_array($ris1, MYSQLI_NUM);
										$stpart=$riga[0];
										$starr=$riga[1];
										$u[$i]=$riga[2];
										$p[$i]=$riga[3];
										
										// echo "INSERT INTO  viaggi VALUES('$partenzaa','$partenza','$u','$p')<BR>";
										// echo "INSERT INTO  viaggi VALUES('$partenza','$arrivoo','$u','$p')<BR>";
										// $query = "INSERT INTO  viaggi VALUES('$partenzaa','$partenza','$u','$p')";
										// $ris21 = mysqli_query($conn, $query);
										// $query = "INSERT INTO  viaggi VALUES('$partenza','$arrivoo','$u','$p')";
										// $ris21 = mysqli_query($conn, $query);
									}
									//devo cancellare la tratta grossa
									$query = "DELETE FROM viaggi where partenza = '$stpart' and arrivo = '$starr'";
									if(!($ris21 = mysqli_query($conn, $query)))
										throw new Exception("Comando fallito");
									for ($i=0; $i<$inq; $i++){
										$uu=$u[$i];
										$pp=$p[$i];
										
										$query = "INSERT INTO  viaggi VALUES('$stpart','$partenza','$uu','$pp')";
										if(!($ris21 = mysqli_query($conn, $query)))
											throw new Exception("Comando fallito");
										$query = "INSERT INTO  viaggi VALUES('$partenza','$starr','$uu','$pp')";
										if(!($ris21 = mysqli_query($conn, $query)))
											throw new Exception("Comando fallito");
									}

									// $query = "INSERT INTO  viaggi VALUES('$stpart','$partenza','$email','$nump')";
									// if(!($ris21 = mysqli_query($conn, $query)))
									// 	throw new Exception("Comando fallito");
									$query = "INSERT INTO  viaggi VALUES('$partenza','$starr','$email','$nump')";
									if(!($ris21 = mysqli_query($conn, $query)))
										throw new Exception("Comando fallito");
									$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
									if(!($ris = mysqli_query($conn, $query)))
											throw new Exception("Comando fallito");
									echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
									mysqli_commit($conn);
									mysqli_autocommit($conn,true);
									mysqli_close($conn);
									$_SESSION['flag']=1;
									
									die();

								
							}
						
					}

				} //fine for esterno
			
		}//2
	
	//Fine caso finisco fuori e parto da una tratta/stazione esistente


	//Inizio caso stazioni intermedie con partenza e arrivo NON esistenti compresi tra inizio e fine
	//seleziono tratta in cui salgo
	$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi WHERE partenza <= '$partenza' AND arrivo > '$partenza' GROUP BY partenza,arrivo for update";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comando fallito");
	$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
		if(mysqli_num_rows($ris) > 0){ //se esiste la tratta di partenza
			if ($riga[2]+$nump <= $cap){//..e ci sto
				
				$part = $riga[0];
				$arr = $riga[1];
			}
			else {//non ci sto nella tratta di partenza
				echo "Posti esauriti nella tratta di partenza.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
				mysqli_close($conn);
				die();
			}
			//conrollo se ci sto nella tratta di arrivo
			$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi WHERE partenza < '$arrivo' AND arrivo >= '$arrivo' GROUP BY partenza,arrivo";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
				if(mysqli_num_rows($ris) > 0){ //se esiste anche la tratta di arrivo
					if ($riga[2]+$nump <= $cap){//..e ci sto
						
						$partb = $riga[0];
						$arrb = $riga[1];
					}
					else { //non ci sto nella tratta di arrivo
						mysqli_close($conn);
						echo "Posti esauriti nella tratta di arrivo.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
						die();
					}
					//cerco tratte intermedie 
					$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi WHERE partenza > '$partenza' AND arrivo < '$arrivo' GROUP BY partenza,arrivo";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
					if(mysqli_num_rows($ris) > 0){
						for ($i=0; $i<mysqli_num_rows($ris); $i++){
							$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
							
							if(($riga[2] + $nump) > $cap){//se non ci sto..
							echo "<BR><BR>Impossibile inserire nuovo viaggio<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
							mysqli_close($conn);
							die();
						}
						else { //..atrimenti
							$partenze[$i]=$riga[0];
							$arrivi[$i]=$riga[1];
							$comprese=1;
						}
						}
					}	 
					//ci sto in tutte. Posso proseguire ad inserire nelle stazioni intermedie
					$index = mysqli_num_rows($ris);
					//Inserisco le tratte
					for ($i=0; $i < $index; $i++){
						$p = $partenze[$i];
						$a = $arrivi[$i];
						$query = "INSERT INTO viaggi VALUES('$p', '$a', '$email', '$nump')";
						echo "INSERT INTO viaggi VALUES('$p', '$a', '$email', '$nump')";
						if(!($ris = mysqli_query($conn, $query)))
							throw new Exception("Comando fallito");
					}
				}
				//fine ci sono tratte intermedie

				//Tratte intermedie inserite. Ora prima e ultima
				//devo smontare la tratta di partenza e ricostruirla  se $part è diverso da $partenza
				//cerco utenti e passeggeri che sono in questa tratta
				if($part!=$partenza){
				$query = "SELECT  utente, passeggeri FROM viaggi WHERE partenza = '$part' AND arrivo = '$arr'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					
					for ($i=0; $i<mysqli_num_rows($ris); $i++){
						$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
						$utentia[$i] = $riga[0];
						$passeggeria[$i] = $riga[1];

					}
					$utentia[mysqli_num_rows($ris)]=$email;
					$passeggeria[mysqli_num_rows($ris)]=$nump;

				$index = mysqli_num_rows($ris);
				//cancello vecchia tratta
				$query = "DELETE FROM `viaggi` WHERE partenza = '$part' AND arrivo = '$arr'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					
				//Inserisco nuove tuple  
				for ($i=0;$i<$index;$i++){
					
					$p=$passeggeria[$i];
					$u=$utentia[$i];
					$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$part', '$partenza','$u','$p')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
				}
				for ($i=0;$i<$index+1;$i++){
					
					$p=$passeggeria[$i];
					$u=$utentia[$i];
					$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$partenza', '$arr','$u','$p')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
				}
				
			}
			else {
				
				$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$partenza', '$arr','$email','$nump')";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");

			}
				//devo smontare la tratta di arrivo e ricostruirla
				//cerco utenti e passeggeri che sono in questa tratta
			if($arrb!=$arrivo){
				$query = "SELECT  utente, passeggeri FROM viaggi WHERE partenza = '$partb' AND arrivo = '$arrb'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					
					for ($i=0; $i<mysqli_num_rows($ris); $i++){
						$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
						$utentib[$i] = $riga[0];
						$passeggerib[$i] = $riga[1];
					}
					$utentib[mysqli_num_rows($ris)]=$email;
					$passeggerib[mysqli_num_rows($ris)]=$nump;
				$index = mysqli_num_rows($ris);
				//cancello vecchia tratta
				$query = "DELETE FROM `viaggi` WHERE partenza = '$partb' AND arrivo = '$arrb'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
					
				//Inserisco nuove tuple  
				for ($i=0;$i<$index;$i++){
					$p=$passeggerib[$i];
					$u=$utentib[$i];
					
					$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$partb', '$arrivo','$u','$p')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
					
				}
				for ($i=0;$i<$index;$i++){
					$p=$passeggerib[$i];
					$u=$utentib[$i];
					
					if($u!=$email){
						$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$arrivo', '$arrb','$u','$p')";
						if(!($ris = mysqli_query($conn, $query)))
							throw new Exception("Comando fallito");
					}
				}
				if($partb>$partenza){
					//echo "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$partb', '$arrivo','$email','$nump')<BR>";
					$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$partb', '$arrivo','$email','$nump')";
					if(!($ris = mysqli_query($conn, $query)))
					 throw new Exception("Comando fallito");
				}

			}
			else {
				if($partb>$partenza){
					$query = "INSERT INTO `viaggi`(`partenza`, `arrivo`, `utente`, `passeggeri`) VALUES ('$partb', '$arrivo','$email','$nump')";
					if(!($ris = mysqli_query($conn, $query)))
						throw new Exception("Comando fallito");
				}
			}
				$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
				if(!($ris = mysqli_query($conn, $query)))
					throw new Exception("Comando fallito");
				mysqli_commit($conn);
				mysqli_autocommit($conn,true);
				mysqli_close($conn);
				echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
				$_SESSION['flag']=1;
				die();
			}
			// else {
			// echo "Non esiste la tratta di arrivo.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			// }

				
	// 	}	
	// 	else {
	// 	echo "Non esiste la tratta di partenza.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	// }	
	//Fine caso stazioni intermedie con partenza e arrivo NON esistenti ma compresi tra inizio e fine


		//Inizio caso tratta inesistente prima della prima
		$query = "SELECT  * FROM viaggi WHERE partenza > '$partenza' AND partenza >= '$arrivo' for update";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
		if(mysqli_num_rows($ris)>0){
			//Inserisco 
			$query = "INSERT INTO viaggi VALUES('$partenza', '$arrivo', '$email', '$nump')";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
			echo "Viaggio inserito correttamente4<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			$_SESSION['flag']=1;

			mysqli_commit($conn);
			mysqli_autocommit($conn,true);
			mysqli_close($conn);
			die();
		}
		// else {
		// 	echo "Non e' una sottotratta con inizio prima/dopo.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
		// 	//die();
		// }
		//Fine caso tratta inesistente prima della prima

		

		//Inizio caso tratta inesistente dopo l'ultima 
		$query = "SELECT  * FROM viaggi WHERE arrivo <= '$partenza' AND arrivo < '$arrivo' for update";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
		if(mysqli_num_rows($ris)>0){
			//Inserisco 
			$query = "INSERT INTO viaggi VALUES('$partenza', '$arrivo', '$email', '$nump')";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			$riga = @mysqli_fetch_array($ris, MYSQLI_NUM);
			echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			mysqli_commit($conn);
			mysqli_autocommit($conn,true);
			mysqli_close($conn);
			$_SESSION['flag']=1;
			die();
		}
		// else {
		// 	echo "Non e' una sottotratta con inizio prima/dopo.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
		// 	//die();
		// }
		//Fine caso tratta inesistente dopo l'ultima oppure prima della prima


	//Inizio caso stazioni intermedie con partenza e arrivo esistenti
	$query = "SELECT  partenza,arrivo,sum(passeggeri) FROM viaggi WHERE partenza = '$partenza' AND arrivo = '$arrivo' GROUP BY partenza,arrivo";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comando fallito");
		for ($i=0; $i<mysqli_num_rows($ris); $i++){
				$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
				//$stazioni[$i] = $riga[0];
				
				if(($riga[2] + $nump) > $cap){
					echo "<BR><BR>Impossibile inserire nuovo viaggio<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
					mysqli_close($conn);
					die();
					//$flag =1;
				}
				else {
				$partenze[$i]=$riga[0];
				$arrivi[$i]=$riga[1];
				}
				 
		}
		$index = mysqli_num_rows($ris);
		//Inserisco le tratte
	for ($i=0; $i < $index; $i++){
		$p = $partenze[$i];
		$a = $arrivi[$i];
		$query = "INSERT INTO viaggi VALUES('$p', '$a', '$email', '$nump')";
		if(!($ris = mysqli_query($conn, $query)))
			throw new Exception("Comando fallito");
	}
	
	$query = "UPDATE `utenti` SET richiesto='si' WHERE email='$email'";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Comando fallito");
	mysqli_commit($conn);
	mysqli_autocommit($conn,true);
	mysqli_close($conn);
	echo "Viaggio inserito correttamente<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	$_SESSION['flag']=1;
	die();
	//Fine caso stazioni intermedie con partenza e arrivo esistenti

}
catch (Exception $e){
	mysqli_rollback($conn);
	echo "Rollback ".$e->getMessage();
	echo "<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	mysqli_autocommit($conn,true);
}

//mysqli_autocommit($conn,true);
//mysqli_close($conn);


?>			