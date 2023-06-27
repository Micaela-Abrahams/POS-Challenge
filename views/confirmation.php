<?

// display error codes and messages
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../static/css/confirmation.css">
    <title>Confirmation Page</title>
</head>

<body>
    <h1 id="titleName">
        <span style="color:#008E9B">Click</span> <span style="color:#B6EA7C">& </span><span style="color:#FF8C40">Buy</span>
    </h1>

    <h1 id="confirmationTitle">
        <?php
        // Check if the payment form was submitted
        if (isset($_GET['payment'])) {
            $paymentMethod = $_GET['payment'];

            // Display the payment success message based on the selected payment method
            if ($paymentMethod === "card") {
                echo "Card payment successful";
            } elseif ($paymentMethod === "cash") {
                echo "Cash payment successful";
            } else {
                echo "Invalid payment method";
            }
        } else {
            echo "No payment method selected";
        }
        ?>
    </h1>
</body>

</html>