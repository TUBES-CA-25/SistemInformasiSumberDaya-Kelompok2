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
            background-color: #eee;
            border: 1px solid black;
            color: black;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>ADMIN LOGIN</h2>
    
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert">
            <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/login" method="POST">
        <div class="form-group">
            <label>USERNAME</label>
            <input type="text" name="username" required autofocus autocomplete="off">
        </div>
        
        <div class="form-group">
            <label>PASSWORD</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit">LOGIN →</button>
    </form>
    <div style="text-align:center; margin-top:20px; font-size:12px;">
        <a href="<?= BASE_URL ?>" style="color:black;">&larr; Kembali ke Home</a>
    </div>
</div>

</body>
</html>
