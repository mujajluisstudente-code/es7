<?php
$fileUtenti = 'DatiRegistrazione.json';

function leggiJson($file) {
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

function scriviJson($file, $dati) {
    file_put_contents($file, json_encode($dati, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    if (in_array('', $dati)) {
        header("Location: index.html?msg=Compila+tutti+i+campi&color=red");
        exit;
    }

    if (!filter_var($dati['email'], FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?msg=Email+non+valida&color=red");
        exit;
    }
    if (!preg_match('/^[A-Z0-9]{16}$/', $dati['codice_fiscale'])) {
        header("Location: index.html?msg=Codice+fiscale+non+valido&color=red");
        exit;
    }
    if (!in_array($dati['sesso'], ['M','F','N'])) {
        header("Location: index.html?msg=Seleziona+sesso+valido&color=red");
        exit;
    }

    $utenti = leggiJson($fileUtenti);

    foreach ($utenti as $u) {
        if ($u['username'] === $dati['username']) {
            header("Location: index.html?msg=Username+già+esistente&color=red");
            exit;
        }
        if ($u['codice_fiscale'] === $dati['codice_fiscale']) {
            header("Location: index.html?msg=Codice+fiscale+già+registrato&color=red");
            exit;
        }
    }

    $dati['password'] = password_hash($dati['password'], PASSWORD_DEFAULT);

    $utenti[] = $dati;
    scriviJson($fileUtenti, $utenti);

    header("Location: index.html?msg=Registrazione+completata&color=green");
    exit;
}
