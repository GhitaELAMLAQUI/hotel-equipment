<?php
include("models\model.php");

if (empty($_GET["action"])) $action = "acceuil";
else $action = $_GET["action"];

//inventaire

if ($action=='listeMateriels') {
	$resultat=listeMateriels();
	
	$vue="vListeMateriels.php";
}
elseif ($action=='listeEmploye') {

	$resultat=listeEmploye();
	$vue="vListeEmploye.php";
} 
elseif ($action=='demandes') {

		$resultat=listeDemandes();
		$vue="vDemandes.php";
}
elseif ($action=='formEmploye') {

	$vue="vFormEmploye.php";
}
elseif ($action=='ajouterEmploye') {

	$droit=0;
	if(isset($_POST["Ajouter"])){
		$droit+=1;
	}
	if(isset($_POST["Modifier"])){
		$droit+=2;
	}
	if(isset($_POST["Supprimer"])){
		$droit+=4;
	}
	ajouterEmploye($_POST,$droit);
	$resultat=listeEmploye();
	$vue="vListeEmploye.php";
}
elseif ($action=='detailEmploye') {
		$resultat=detailsEmploye($_GET["id"]);
		$depresult=getDepartement($_GET["id"]);
		$employe = $resultat->fetch();
		$departement=$depresult->fetch();

		$vue = "vDetailEmploye.php";
}elseif ($action=="supprimerEmploye") {
		supprimerEmploye($_GET["id"]);
		$resultat=listeEmploye();
		$vue="vListeEmploye.php";
}elseif ($action=='modifierEmploye') {
	modifierEmploye($_POST,$_GET["id"]);
		$resultat=listeEmploye();
		$vue="vListeEmploye.php";
}
elseif ($action=="detailMateriel") {
		$mat=detailsMateriel($_GET["id"]);
		$emp=getEmployeInv($_GET["id"]);
		$employe=$emp->fetch();
		$materiel=$mat->fetch();
		$vue="vDetailMateriel.php";
}elseif ($action=="supprimerMateriel") {
	libererMateriel($_GET["numSerie"]);
	$resultat=listeMateriels();
	$vue="vListeMateriels.php";
}
 else {
	//controller
	$var="ghita";
	//
	$vue="vAcceuil.php";
}
include("views\\" . $vue);
include("views\gabarit.php");

?>