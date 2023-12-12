<?php

/**
 * Définition d'une classe permettant de gérer les membres 
 * en relation avec la base de données
 *
 */

class MembreManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * verification de l'identité d'un membre (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return membre si authentification ok, false sinon
	 */
	public function verif_identification($login, $password)
	{
		//echo $login." : ".$password;
		$req = "SELECT idmembre, nom, prenom FROM membre WHERE email=:login and password=:password ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login, ":password" => $password));
		if ($data = $stmt->fetch()) {
			$membre = new Membre($data);
			return $membre;
		} else
			return false;
	}

	/**
	 * retourne l'ensemble des membres présents dans la BD 
	 * @return membre[]
	 */
	public function getMembre($idmembre)
	{
		$req = "SELECT idmembre, nom, prenom, anneenaissance, voiture, telportable  FROM membre WHERE idmembre = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idmembre));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		$membre = new Membre($stmt->fetch());
		return $membre;
	}
}
?>