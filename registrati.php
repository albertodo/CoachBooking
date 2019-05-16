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
	
if(!isset($_REQUEST['email']) || !isset($_REQUEST['password'])){
echo "Bad request<BR><BR>Torna indietro: <a href=\"index.php\">Home page <BR></a><BR>";
	die();
}

	$conn = mysqli_connect('localhost','root', '','esame');
		if (!$conn) {
			echo("Errore nella connessione(".mysqli_connect_errno().")" .mysqli_connect_error()); //Poi da togliere
			echo("<BR><BR>Torna indietro: <a href=\"index.php\">Home page <BR></a><BR>");
			die();
		}
		
	$cont=0;
	$email = $_REQUEST['email'];
	$email = mysqli_real_escape_string($conn, $email);
	$psw = $_REQUEST['password'];
	
	if (!preg_match('/^[A-z0-9\.\+_-]+@[A-z0-9\._-]+\.[A-z]{2,6}$/', $email)){ 
		echo("email non valida<BR> Torna indietro: <a href=\"index.php\">Home page <BR></a><BR>");
		// non serve fare strip_tags perchè fa il match con la regex
		die();
		
	}

	if (!preg_match('/(((.*[a-z].*([0-9]|[A-Z]).*)|(.*([0-9]|[A-Z]).*[a-z].*)))/', $psw)){
		echo("Password non valida<BR> Torna indietro: <a href=\"index.php\">Home page <BR></a><BR>");
		die();
		//Fermo il caricamento della pagina
	}


	try{

		$query = "SELECT password FROM utenti WHERE email='$email' for update";
		
		if (!($ris=mysqli_query($conn,$query)))
			throw new Exception("Comando fallito");
		for ($i=0; $i<mysqli_num_rows($ris); $i++){
		$cont++;
		$riga = mysqli_fetch_array($ris, MYSQLI_NUM);
		$passwordNeldb = $riga['0'];
		}
		

		if($cont != 0 && $passwordNeldb == md5($psw)){
			$_SESSION['email']=$email;
			echo "Bentornato $email! <BR><BR>Procedi: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			$_SESSION['flag']=0;
		}
			else if($cont!=0 && $passwordNeldb != md5($psw)){
				echo("Password errata.<BR><BR>Torna indietro: <a href=\"index.php\">Home page <BR></a><BR>");
				//mysqli_commit($conn);
				mysqli_autocommit($conn,true);
				mysqli_close($conn);
				die();
			}
			
		
		else {
			$query = "INSERT into utenti values ('$email',md5('$psw'),'no')";  
			if(!($ris = mysqli_query($conn, $query)))
				throw new Exception("Comando fallito");
			
			echo "Utente: ".$email." Password: ".$psw." registrato";
			$_SESSION['email']=$email;
			$_SESSION['flag']=0;
			mysqli_commit($conn);
			mysqli_autocommit($conn,true);
			mysqli_close($conn);
			echo "<BR><BR>Procedi: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
			}
	}
	catch (Exception $e){
	mysqli_rollback($conn);
	echo "Rollback ".$e->getMessage();
	echo "<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	mysqli_autocommit($conn,true);
}

?>