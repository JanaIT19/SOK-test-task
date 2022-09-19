<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="form-container">
            <form action="/index.php" method="post">
                <div class="form-element">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" pattern="[a-zA-Z0-9]+" required>
                </div>
                <div class="form-element">
                    <label for="password">Password:</label><br>
                    <input type="text" id="password" name="password" required>
                </div>
                <input class="button login" type="submit" value="Login"/>
            </form>
            <div class="tooltip">
                <div class="tooltip-content">
                    <span >Username: admin</span>
                    <br>
                    <span >Password: PAROLE123</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
