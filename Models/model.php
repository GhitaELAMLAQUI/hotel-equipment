<?php

//connexion à la base de données
function ouvrirConnexion(){
	$user = 'root';
	$password = '';
	$db = 'mysql:host=localhost;dbname=savoy';
	try {
		$cn = new PDO($db, $user, $password);
	}
	catch (PDOException $dbex) {
		die("Erreur de connexion : " . $dbex->getMessage() );
	}
	return $cn;
}
function listeMateriels() {
	$cn = ouvrirConnexion();
	return $cn->query("SELECT * from materiel where etat !='disponible'");
}

function listeEmploye() {
	$cn = ouvrirConnexion();
	return $cn->query("SELECT * from employe where role='Employe'  order by dateRecrutement");
}
function detailsEmploye($id) {
	$cn = ouvrirConnexion();
	$req="SELECT * from employe where id=".$id ;
	return $cn->query($req);
}
function detailsMateriel($numSerie){

	$cn = ouvrirConnexion();
	$req="SELECT * from materiel where numSerie=".$numSerie ;
	return $cn->query($req);
}
function libererMateriel($numSerie){
	$cn = ouvrirConnexion();
	$req="UPDATE materiel set etat='disponible',source='inventaire',dateEntree=now() ,idEmp=NULL where numSerie='".$numSerie."'";
	$cn->exec($req);
}
function listeDemandes() {
	$cn = ouvrirConnexion();
	return $cn->query("SELECT  d.numSerie,m.nomMat,d.qte ,dept.nom,d.date,emp.nom,emp.prenom 
		from demande d,employe emp,departement dept ,materiel m
		where d.numSerie=m.numSerie and d.idemp=emp.id and emp.idDept=dept.id ");
}
function supprimerEmploye($id){
	$cn = ouvrirConnexion();
	$req="delete from employe where id=".$id;
	$resultat = $cn->exec($req);
}
function getDepartement($id){
	$cn = ouvrirConnexion();
	$req="SELECT d.nom ,e.idDept from departement d,employe e where d.id=e.idDept and e.id=".$id ;
	return $cn->query($req);	
}

function modifierEmploye($t,$id){
	$cn = ouvrirConnexion();
	$deptId=getDepartmentId($t["departement"]);
	$req="UPDATE employe set nom = '".$t["nom"]."', prenom = '".$t["prenom"]."',dateNaissance='".$t["dateDeNaissance"]."' , droitAcces=".$t["droit"]." , idDept='".$deptId."' where id=".$id;
	$resultat = $cn->exec($req);	
}
function getEmployeInv($numSerie){
	$cn = ouvrirConnexion();

	$req="SELECT e.nom,e.prenom,d.nom from employe e,departement d,materiel m where  m.idEmp like e.id and e.idDept like d.id and m.numSerie=  '".$numSerie."'";
	return $cn->query($req);
	}
function AjouterEmploye($t,$droit) {

$cn = ouvrirConnexion();

//pour offrir un id automatiquement
$m=$cn->query("SELECT MAX(id) from employe");
$result=$m->fetch();
$max=$result[0];
$code=intval($max)+1;
	
$deptId=getDepartmentId($t["departement"]);

$Rq = "INSERT into employe values ('".$code."','" . $t["nom"] . "','" . $t["prenom"] .  "','" . $t["dateNaissance"] . "',now(),".$droit.",'".$t["role"]."','".$deptId."');";
$resultat = $cn->exec($Rq);
}

function getDepartmentId($nom){
$cn = ouvrirConnexion();
$stmt=$cn->prepare("SELECT id from departement where nom = ?");
$stmt->execute([$nom]);
$id=$stmt->fetch();
return $id[0];
}



?>