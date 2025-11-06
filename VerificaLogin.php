<?php
$fileUtenti = 'DatiRegistrazione.json';

function leggiJson($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    if (empty($content)) {
        return [];
    }
    return json_decode($content, true) ?? [];
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        header("Location: login.html?msg=Compila+tutti+i+campi&color=red");
        exit;
    }
    
    $utenti = leggiJson($fileUtenti);
    $utenteTrovato = false;
    
    if (!is_array($utenti)) {
        header("Location: login.html?msg=Errore+nel+caricamento+degli+utenti&color=red");
        exit;
    }
    
    foreach ($utenti as $utente) {
        if ($utente['username'] === $username) {
            $utenteTrovato = true;
            if (password_verify($password, $utente['password'])) {
                $_SESSION['utente_loggato'] = true;
                $_SESSION['username'] = $utente['username'];
                $_SESSION['nome'] = $utente['nome'];
                $_SESSION['cognome'] = $utente['cognome'];
                $_SESSION['email'] = $utente['email'];
                $_SESSION['codice_fiscale'] = $utente['codice_fiscale'];
                $_SESSION['data_nascita'] = $utente['data_nascita'];
                $_SESSION['sesso'] = $utente['sesso'];
                
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
} else {
    header("Location: login.html");
    exit;
}
?>
