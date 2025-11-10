<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title><?= $title; ?></title>
</head>

<body>
    <!-- header -->
    <?php require_once __DIR__ . '/../views/header.php'; ?>
    <!-- Dynamic Inclusion of view -->
    <?php require_once __DIR__ . '/../views/' . $viewName . '.php'; ?>
    <!-- Footer -->
    <?php require_once __DIR__ . '/../views/footer.php'; ?>
  
</body>

</html>