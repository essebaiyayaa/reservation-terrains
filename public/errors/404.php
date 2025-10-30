<?php $title = "404 Not Found"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
            color: #343a40;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            font-size: 6rem;
            margin: 0;
            color: #e74c3c;
        }
        p {
            font-size: 1.5rem;
            margin: 1rem 0;
        }
        a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            transition: color 0.3s;
        }
        a:hover {
            color: #1d6fa5;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Oops! The page you are looking for does not exist.</p>
    <a href="/">Go back home</a>
</body>
</html>
