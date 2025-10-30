<?php $title = "403 Forbidden"; ?>
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
            color: #495057;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            font-size: 6rem;
            color: #c0392b;
        }
        p {
            font-size: 1.5rem;
            margin: 1rem 0;
        }
        a {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
            transition: color 0.3s;
        }
        a:hover {
            color: #1d6fa5;
        }
    </style>
</head>
<body>
    <h1>403</h1>
    <p>Access denied. You do not have permission to view this page.</p>
    <a href="/">Go back home</a>
</body>
</html>
