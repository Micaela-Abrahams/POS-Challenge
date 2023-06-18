<?php
// Start Session:
session_start();

include 'data/data.php'; // Data from the data.php page is included on the index.php page
include 'include/addItem.php'; // Function from the addItem.php page is included on the index.php page

// display error codes and messages
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Create a session variable called "order" as an empty array
if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = array();
}

// 2. Create a session variable called "orderTotal" if it is not set or if the "order" session variable is empty
if (!isset($_SESSION['orderTotal']) || empty($_SESSION['order'])) {
    $_SESSION['orderTotal'] = "0.00";
} elseif (empty($_SESSION['order'])) {
    // If the "order" session variable is empty, reset the "orderTotal" to 0
    $_SESSION['orderTotal'] = "0.00";
}

// // Handle item selection
// Handle item selection
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['selectedItemValue'])) {
        $selectedItem = $_GET['selectedItemValue'];
        $itemPrice = getItemPrice($selectedItem);

        // Add the selected item to the "order" session variable
        $_SESSION['order'][] = $selectedItem;

        // Update the "orderTotal" session variable by adding the item price
        $_SESSION['orderTotal'] = number_format(floatval($_SESSION['orderTotal']) + floatval($itemPrice), 2);

        // Redirect to the same page to avoid form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link rel="stylesheet" href="./static/css/style.css">
</head>

<body>
    <h1>
        <span style="color:red">Select</span> and <span style="color:blue">Save</span>
    </h1>

    <hr>

    <!--Till Display -->

    <div class="till__display">
        <div>
            <span class="till__console">
                Amount: R <span><?php echo $_SESSION['orderTotal']; ?></span>
            </span>
        </div>
    </div>

    <hr>

    <!-- Menu Items to be displayed -->
    <section>
        <form class="items" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">

            <?php
            // The for loop iterates over each item in the $items array (data.php file).
            // the items details gets extracted  using the key/value pair & then gets echoes out in the buttons
            foreach ($items as $item) {
                $itemName = $item['name'];
                $itemPrice = $item['price'];
                $itemBarcode = $item['barcode'];
            ?>
                <button type="submit" name="selectedItemValue" value="<?php echo $itemName; ?>" class="item">
                    <h3><?php echo $itemName; ?></h3>
                    <p>Price: <?php echo "R" . $itemPrice . ".00"; ?></p>
                    <p>Barcode: <?php echo $itemBarcode; ?></p>
                </button>
            <?php
            }
            ?>
        </form>
    </section>

    <!-- Payment Button -->
    <form action="./views/payments.php" method="get" class="checkout">
        <input type="hidden" name="subTotal" value="sub total amount">
        <!-- When payment button is selected user will be directed to payments.php page -->
        <button type="submit">
            Proceed to payment
        </button>
    </form>


</body>

</html>