<?php

// display error codes and messages
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include the calculateVAT() function
include __DIR__ . '/../include/calculateVAT.php';
include __DIR__ . '/../data/data.php';


// Retrieve the stored table data from the session variable
$tableData = isset($_SESSION['tableData']) ? $_SESSION['tableData'] : '';

// Calculate the total amount for the items
$totalAmount = 0;
if (!empty($_SESSION['order'])) {
    foreach ($_SESSION['order'] as $selectedItem) {
        $menuItem = null;

        // Find the selected item in the $items array
        foreach ($items as $item) {
            if ($item['name'] == $selectedItem) {
                $menuItem = $item;
                break;
            }
        }

        // Add the item's price to the total amount
        if ($menuItem !== null) {
            $totalAmount += $menuItem['price'];
        }
    }
}

// Calculate the VAT amount using the calculateVAT() function
$vatAmount = calculateVAT($totalAmount);

// Clear the table data from the session
unset($_SESSION['tableData']);



// Clear the table data from the session
unset($_SESSION['tableData']);

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
    <!-- Links to the CSS Stylesheets -->
    <link rel="stylesheet" href="./../static/css/style.css">
    <link rel="stylesheet" href="./../static/css/payments.css">
</head>

<body>
    <!-- Heading Title -->
    <h1 id="titleName">
        <span style="color:#008E9B">Click</span> <span style="color:#B6EA7C">& </span><span style="color:#FF8C40">Buy</span>
    </h1>

    <!-- Back Button that will redirect user to index.php page -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <button style="background-color:#008E9B" type="submit" name="payment">Back</button>

    </form>

    <hr>

    <!-- Display items from Table in index.php -->
    <h2>Items Purchased:</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Barcode</th>

            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are any selected items in the session
            if (!empty($_SESSION['order'])) {
                // Iterate over the selected items and display them in the table
                foreach ($_SESSION['order'] as $selectedItem) {
                    $menuItem = null;

                    // Find the selected item in the $items array
                    foreach ($items as $item) {
                        if ($item['name'] == $selectedItem) {
                            $menuItem = $item;
                            break;
                        }
                    }

                    // Display the item in the table row
                    if ($menuItem !== null) {
                        echo '<tr>';
                        echo '<td>' . $menuItem['name'] . '</td>';
                        echo '<td>' . "R" . $menuItem['price'] . ".00" . '</td>';
                        echo '<td>' . $menuItem['barcode'] . '</td>';
                        echo '<td>';
                        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
                        echo '<input type="hidden" name="removeItemValue" value="' . $menuItem['name'] . '">';

                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
            }

            ?>
        </tbody>
    </table>

    <hr>

    <!-- Total Costs Displayed -->
    <h2 id="totalAmount">
        <!-- Total amount of Items displayed -->
        Amount excluding VAT: R<span><?php echo number_format($totalAmount, 2); ?></span>
        <br>
        <!-- VAT amount displayed -->
        VAT (15%): R <span><?php echo number_format($vatAmount, 2); ?></span>
        <br>
        <br>
        <!-- Total cost of Items + VAT Displayed -->
        <b>Amount including VAT: R<span><?php echo number_format($totalAmount + $vatAmount, 2); ?></span></b>
    </h2>

    <!-- Buttons for user to select Cash/Card Payment -->
    <form action="./../views/confirmation.php" method="get">
        <button type="submit" name="payment" value="card" class="card">Pay with card</button>
        <button type="submit" name="payment" value="cash" class="cash">Pay with cash</button>
    </form>

</body>

</html>