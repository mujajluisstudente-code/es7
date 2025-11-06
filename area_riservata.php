<?php
session_start();

if (!isset($_SESSION['utente_loggato']) || $_SESSION['utente_loggato'] !== true) {
    header("Location: login.html?msg=Accesso+non+autorizzato&color=red");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Riservata</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2b7a78;
        }
        
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-group {
            margin-bottom: 15px;
        }
        
        .info-label {
            font-weight: bold;
            color: #2b7a78;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #2b7a78;
        }
        
        .logout-btn {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .profile-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Benvenuto, <?php echo htmlspecialchars($_SESSION['nome'] . ' ' . $_SESSION['cognome']); ?>!</h1>
            <p>Questa è la tua area riservata</p>
        </div>
        
        <div class="profile-info">
            <div class="info-group">
                <span class="info-label">Username:</span>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            </div>
            
            <div class="info-group">
                <span class="info-label">Email:</span>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
            </div>
            
            <div class="info-group">
                <span class="info-label">Codice Fiscale:</span>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['codice_fiscale']); ?></div>
            </div>
            
            <div class="info-group">
                <span class="info-label">Data di Nascita:</span>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['data_nascita']); ?></div>
            </div>
            
            <div class="info-group">
                <span class="info-label">Sesso:</span>
                <div class="info-value">
                    <?php 
                    $sesso = $_SESSION['sesso'];
                    echo $sesso === 'M' ? 'Maschile' : ($sesso === 'F' ? 'Femminile' : 'Preferisco non dirlo');
                    ?>
                </div>
            </div>
        </div>
        
        <div style="text-align: center;">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>