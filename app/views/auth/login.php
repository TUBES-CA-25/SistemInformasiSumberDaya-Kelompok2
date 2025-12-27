<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SISDA</title>
    <style>
        body {
            font-family: monospace;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 40px;
            border: 2px solid black;
            width: 300px;
            box-shadow: 10px 10px 0px black;
        }
        h2 {
            text-align: center;
            margin-top: 0;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid black;
            font-family: inherit;
        }
        button {
            width: 100%;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-weight: bold;
            margin-top: 10px;
        }
        button:hover {
            opacity: 0.8;
        }
        .alert {
            border: 2px solid;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 0.9em;
            font-weight: normal;
            line-height: 1.4;
        }
        .alert.error {
            background-color: #ffeaea;
            border-color: #d32f2f;
            color: #b71c1c;
        }
        .alert.success {
            background-color: #e8f5e8;
            border-color: #4caf50;
            color: #2e7d32;
        }
        .form-info {
            background-color: #f0f8ff;
            border: 1px solid #2196f3;
            padding: 12px;
            margin-top: 20px;
            font-size: 0.8em;
            text-align: center;
            color: #1976d2;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>ADMIN LOGIN</h2>
    
    <?php 
    $flashError = $_SESSION['flash']['error'] ?? null;
    if ($flashError): 
        unset($_SESSION['flash']['error']);
    ?>
        <div class="alert error">
            <?= $flashError; ?>
        </div>
    <?php endif; ?>
    
    <?php 
    $flashSuccess = $_SESSION['flash']['success'] ?? null;
    if ($flashSuccess): 
        unset($_SESSION['flash']['success']);
    ?>
        <div class="alert success">
            <?= $flashSuccess; ?>
        </div>
    <?php endif; ?>

    <form action="<?= PUBLIC_URL ?>/auth" method="POST">
        <div class="form-group">
            <label>USERNAME</label>
            <input type="text" name="username" required autofocus autocomplete="off" 
                   placeholder="Masukkan username admin"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>PASSWORD</label>
            <input type="password" name="password" required
                   placeholder="Masukkan password">
        </div>
        
        <button type="submit">LOGIN →</button>
    </form>
    
    <div class="form-info">
        <strong>📝 Kredensial Default:</strong><br>
        Username: <code>admin</code><br>
        Password: <code>admin123</code>
    </div>
    
    <div style="text-align:center; margin-top:20px; font-size:12px;">
        <a href="<?= rtrim(PUBLIC_URL, '/') . '/home' ?>" style="color:black;">&larr; Kembali ke Home</a>
    </div>
</div>

</body>
</html>
    }