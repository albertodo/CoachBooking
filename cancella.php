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

if (isset($_SESSION['email']))
	$email=$_SESSION['email'];
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
$conn = mysqli_connect('localhost','root', '','esame');
if (!$conn) {
	echo("Errore nella connessione(".mysqli_connect_errno().")" .mysqli_connect_error()); //Poi da togliere
	die();
}
try{
	
	mysqli_autocommit($conn,false);
	$query = "DELETE FROM viaggi WHERE utente='$email'";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Cancellazione fallita");
	$query = "UPDATE `utenti` SET richiesto='no' WHERE email='$email'";
	if(!($ris = mysqli_query($conn, $query)))
		throw new Exception("Cancellazione fallita");
	mysqli_commit($conn);
	mysqli_autocommit($conn,true);
	mysqli_close($conn);
	echo "Prenotazione cancellata.<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	die();

}
catch (Exception $e){
	mysqli_rollback($conn);
	echo "Rollback ".$e->getMessage();
	echo "<BR><BR>Torna indietro: <a href=\"homeAutenticato.php\">Home page <BR></a><BR>";
	mysqli_autocommit($conn,true);
}
mysqli_autocommit($conn,true);
mysqli_close($conn);


?>