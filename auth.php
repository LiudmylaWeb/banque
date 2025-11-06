<?php
class Auth {
    // Cette ligne déclare une propriété protégée $pdo, qui est utilisée pour stocker l'objet PDO, représentant la connexion à la base de données.
    protected $pdo;
    protected $sessionName = 'user';

    public function __construct($pdo) {
        $this->pdo = $pdo;

    }

    public function register($username, $password, $nom, $categorie = 'Agent') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO employe (login, motDePasse, nom, categorie) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $nom, $categorie]);
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM employe WHERE login = ? AND actif = 1");
        // $username is the field login 
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['motDePasse'])) {
            $_SESSION[$this->sessionName] = $user['numEmploye'];
            return true;
        }
        return false;
    }

    public function logout() {
        unset($_SESSION[$this->sessionName]);
    }

    // Renamed and adjusted method to check for user role
    public function checkRole($requiredRole) {
        if (!isset($_SESSION[$this->sessionName])) {
            return false;
        }

        $userId = $_SESSION[$this->sessionName];
        $stmt = $this->pdo->prepare("SELECT categorie FROM employe WHERE numEmploye = ?");
        $stmt->execute([$userId]);
        $userCategorie = $stmt->fetchColumn();

        return $requiredRole == $userCategorie;
    }

    public function isLoggedIn() {
        return isset($_SESSION[$this->sessionName]);
    }

    public function id() {
        if(!$this->isLoggedIn()){
            return null;
        }
        return $_SESSION[$this->sessionName];
    }

    // Added method to get user details
    public function getUser() {
        if(!$this->isLoggedIn()){
            return null;
        }
        $userId = $_SESSION[$this->sessionName];
        $stmt = $this->pdo->prepare("SELECT * FROM employe WHERE numEmploye = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}