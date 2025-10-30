<?php $title = "500 Internal Server Error"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f3f5;
            color: #212529;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            font-size: 6rem;
            color: #e67e22;
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
    <h1>500</h1>
    <p>Something went wrong on our server. Please try again later.</p>
    <a href="/">Go back home</a>
</body>
</html>
