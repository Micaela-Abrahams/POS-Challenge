<?php

// display error codes and messages
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    if ( isset($_POST['selectedItemValue']) ) {
       
        // code to add item
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&S POS</title>
    <link rel="stylesheet" href="./static/css/style.css">
</head>
<body>
    <h1>
        <span style="color:red">Select</span> and <span style="color:blue">Save</span>
    </h1>

    <hr>

    <div class="till__display">
        <div>
            <span class="till__console">
                Amount: R <span>0</span>
            </span>
        </div>
    </div>

    <hr>

    <section >
        <form class="items" action=" <?php $_SERVER['PHP_SELF'] ?>" method="post">
            <?php
                for ($i=0; $i < 3; $i++) { 
            ?>   
                <button type="submit" name="selectedItemValue" value="" class="item">
                    <h3>
                        Dummy Item <?php echo $i; ?>
                    </h3>
                </button>
            <?php
                }
            ?>
        </form>
    </section>

    <form action="./views/checkout.php" method="get" class="checkout">
        <input type="hidden" name="subTotal" value="sub total amount">
        <button type="submit">
            Proceed to payment
        </button>
    </form>


</body>
</html>