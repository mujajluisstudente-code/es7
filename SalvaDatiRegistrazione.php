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

function scriviJson($file, $dati) {
    // Assicuriamoci che la directory esista e sia scrivibile
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    $result = file_put_contents($file, json_encode($dati, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        error_log("Errore nella scrittura del file: " . $file);
        return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validazione dei dati
    $dati = [
        'nome' => trim($_POST['nome'] ?? ''),
        'cognome' => trim($_POST['cognome'] ?? ''),
        'username' => trim($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'email' => trim($_POST['email'] ?? ''),
        'codice_fiscale' => strtoupper(trim($_POST['codice_fiscale'] ?? '')),
        'data_nascita' => trim($_POST['data_nascita'] ?? ''),
        'sesso' => $_POST['sesso'] ?? ''
    ];

    // Verifica campi obbligatori
    foreach ($dati as $key => $value) {
        if (empty($value)) {
            header("Location: index.html?msg=Campo+" . urlencode($key) . "+obbligatorio&color=red");
            exit;
        }
    }

    // Validazione email
    if (!filter_var($dati['email'], FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?msg=Email+non+valida&color=red");
        exit;
    }

    // Validazione codice fiscale
    if (!preg_match('/^[A-Z0-9]{16}$/', $dati['codice_fiscale'])) {
        header("Location: index.html?msg=Codice+fiscale+non+valido&color=red");
        exit;
    }

    // Validazione sesso
    if (!in_array($dati['sesso'], ['M','F','N'])) {
        header("Location: index.html?msg=Seleziona+sesso+valido&color=red");
        exit;
    }

    // Carica utenti esistenti
    $utenti = leggiJson($fileUtenti);
    if ($utenti === null) {
        $utenti = [];
    }

    // Verifica duplicati
    foreach ($utenti as $u) {
        if ($u['username'] === $dati['username']) {
            header("Location: index.html?msg=Username+già+esistente&color=red");
            exit;
        }
        if ($u['email'] === $dati['email']) {
            header("Location: index.html?msg=Email+già+registrata&color=red");
            exit;
        }
        if ($u['codice_fiscale'] === $dati['codice_fiscale']) {
            header("Location: index.html?msg=Codice+fiscale+già+registrato&color=red");
            exit;
        }
    }

    // Hash della password
    $dati['password'] = password_hash($dati['password'], PASSWORD_DEFAULT);

    // Aggiungi nuovo utente
    $utenti[] = $dati;

    // Salva nel file JSON
    if (scriviJson($fileUtenti, $utenti)) {
        header("Location: index.html?msg=Registrazione+completata+con+successo&color=green");
    } else {
        header("Location: index.html?msg=Errore+durante+la+registrazione&color=red");
    }
    exit;
}
?>
