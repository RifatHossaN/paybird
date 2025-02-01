<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>error</title>
</head>
<body>
    <h1>Something Unexpected Happend</h1>
    <?php
    session_start();
    
    echo $_SESSION['userID'];
    echo $_SESSION['name'];
    echo $_SESSION['username'];
    echo $_SESSION['superuser'];
    ?>

</body>
</html>