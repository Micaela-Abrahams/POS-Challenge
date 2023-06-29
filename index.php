<?php
// Start Session:
session_start();

include 'data/data.php'; // Data from the data.php page is included on the index.php page
include 'include/addItem.php'; // Function from the addItem.php page is included on the index.php page
include 'model/MenuItem.php'; // Data from the MenuItem class is included

// display error codes and messages
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// The below code is used checks if the "order" variable has been set & will result in an empty array to store the product items
if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = array();
}

// The below code checks if a a variable called "orderTotal" is not set OR if the "order" variable is empty
if (!isset($_SESSION['orderTotal']) || empty($_SESSION['order'])) {
    $_SESSION['orderTotal'] = "0.00"; // If the "orderTotal" variable is not set - the amount of "0.00" will be displayed.
} elseif (empty($_SESSION['order'])) {
    $_SESSION['orderTotal'] = "0.00"; // Else, if the "order" variable is empty - the "orderTotal" will be reset to 0
}

// The following code handles the item selection
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

// Load the menu items and store them in the session variable 'menuItems'
$menuItems = MenuItem::loadData();
$_SESSION['menuItems'] = $menuItems;

// Handle adding item to the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selectedItemValue'])) {
        $selectedItem = $_POST['selectedItemValue'];

        // Add the selected item to the "order" session variable
        $_SESSION['order'][] = $selectedItem;

        // Redirect to the same page to avoid form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedItemValue'])) {
    // Get the selected item value from the form
    $selectedItemValue = $_POST['selectedItemValue'];

    // Retrieve the menu items from the session
    $menuItems = $_SESSION['menuItems'];

    // Search for the selected item in the menu items
    foreach ($menuItems as $index => $menuItem) {
        if ($menuItem->getName() === $selectedItemValue) {
            // Add the selected item to the orderItems array in the session
            $_SESSION['orderItems'][] = $menuItem;
            break;
        }
    }

    // Redirect to the payments page
    header("Location: payments.php");
    exit();
}

// Generate the table with order details
$tableData = '
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Price</th>
      <th>Barcode</th>
    </tr>
  </thead>
  <tbody>';

// Check if there are any selected items in the session
if (!empty($_SESSION['order'])) {
    // Iterate over the selected items and add them to the table data
    foreach ($_SESSION['order'] as $selectedItem) {
        $menuItem = null;

        // Find the selected item in the $items array
        foreach ($items as $item) {
            if ($item['name'] == $selectedItem) {
                $menuItem = $item;
                break;
            }
        }

        // Add the item to the table data
        if ($menuItem !== null) {
            $tableData .= '
            <tr>
                <td>' . $menuItem['name'] . '</td>
                <td>' . "R" . $menuItem['price'] . ".00" . '</td>
                <td>' . $menuItem['barcode'] . '</td>
            </tr>';
        }
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
    <!-- Link for the CSS Stylesheet -->
    <link rel="stylesheet" href="./static/css/style.css">
</head>

<body>
    <!-- Heading Title -->
    <h1 id="titleName">
        <span style="color:#008E9B">Click</span> <span style="color:#B6EA7C">& </span><span style="color:#FF8C40">Buy</span>
    </h1>

    <hr>
    <!-- Menu Items to be displayed -->
    <section>
        <form class="items" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">

            <?php
            // The for loop iterates over each item in the $items array data.php file.
            // The items details gets extracted using the key/value pair & then gets echoed out.
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

    <hr>

    <!-- Table to display selected items -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Barcode</th>
                <th>Remove Item</th>
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

                    // Display the items in the table row
                    if ($menuItem !== null) {
                        echo '<tr>';
                        echo '<td>' . $menuItem['name'] . '</td>';
                        echo '<td>' . "R" . $menuItem['price'] . ".00" . '</td>';
                        echo '<td>' . $menuItem['barcode'] . '</td>';
                        echo '<td>';
                        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
                        echo '<input type="hidden" name="removeItemValue" value="' . $menuItem['name'] . '">';
                        echo '<button type="submit" name="removeItem">Remove Item</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
            }

            // This code block will handle removing items from the table
            if (isset($_POST['removeItem'])) {
                $removeItem = $_POST['removeItemValue'];
                // Find the index of the selected item in the $_SESSION['order'] array
                $index = array_search($removeItem, $_SESSION['order']);
                // Remove the item from the $_SESSION['order'] array

                if ($index !== false) {
                    unset($_SESSION['order'][$index]);

                    // Recalculate the total order amount
                    $totalAmount = 0;
                    foreach ($_SESSION['order'] as $selectedItem) {
                        foreach ($items as $item) {
                            if ($item['name'] == $selectedItem) {
                                $totalAmount += $item['price'];
                                break;
                            }
                        }
                    }
                    $_SESSION['orderTotal'] = number_format($totalAmount, 2);
                }

                // Redirect back to the same page to update the display
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>
        </tbody>
    </table>

    <!--Till Display -->
    <div class="till__display">
        <div>
            <span class="till__console">
                Amount: R <span><?php echo $_SESSION['orderTotal']; ?></span>
            </span>
        </div>
    </div>

    <hr>

    <!-- Payment Button -->
    <form action=" ./views/payments.php" method="get" class="checkout">
        <input type="hidden" name="subTotal" value="sub total amount">
        <?php if (empty($_SESSION['order'])) : ?>
            <p class="error-message">Please select items before confirming the order.</p>
        <?php else : ?>
            <button type="submit">
                Confirm Order
            </button>
        <?php endif; ?>
    </form>

</body>

</html>