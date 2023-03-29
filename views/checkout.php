<?php

// display error codes and messages
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

// redirect back to index if payment button is selected
    if (isset($_GET['payment'])) {
        session_unset();
        header("Location: ./../");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&S POS | Pay</title>
    <link rel="stylesheet" href="./../static/css/style.css">
</head>
<body>
    <h1>
        <span style="color:red">Select</span> and <span style="color:blue">Save</span>
    </h1>

    <hr>

    <h2>Items Purchased:</h2>

    <ul>
        <li>item 1</li>
        <li>item 2</li>
        <li>item 3</li>
    </ul>

    <hr>

    <h2>
        Amount: R<span>0.00</span>
        <br>
        VAT Amount: R <span>0.00</span>
        <br>
        <br>
        Subtotal for all items: R<span>0.00</span>
    </h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <button style="background-color:red" type="submit" name="payment">Pay with card</button>
        <button style="background-color:cornflowerblue" type="submit" name="payment">Pay with cash</button>
    </form>

</body>

</html>