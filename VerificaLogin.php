<?php
$fileUtenti = 'DatiRegistrazione.json';

function leggiJson($file) {
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        header("Location: login.html?msg=Compila+tutti+i+campi&color=red");
        exit;
    }
    $utenti = leggiJson($fileUtenti);
    $utenteTrovato = false;
    
    foreach ($utenti as $utente) {
        if ($utente['username'] === $username) {
            $utenteTrovato = true;
            if (password_verify($password, $utente['password'])) {
                session_start();
                $_SESSION['utente_loggato'] = true;
                $_SESSION['username'] = $utente['username'];
                $_SESSION['nome'] = $utente['nome'];
                $_SESSION['cognome'] = $utente['cognome'];
                $_SESSION['email'] = $utente['email'];
                
                header("Location: area_riservata.php");
                exit;
            } else {
                header("Location: login.html?msg=Password+errata&color=red");
                exit;
            }
        }
    }
    if (!$utenteTrovato) {
        header("Location: login.html?msg=Utente+non+trovato&color=red");
        exit;
    }
}
?>