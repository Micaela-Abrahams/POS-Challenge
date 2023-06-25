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

$tableData .= '
  </tbody>
</table>';

// Store the table data in a session variable
$_SESSION['tableData'] = $tableData;
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
        <span style="color:red">Click</span> & <span style="color:blue">Buy</span>
    </h1>

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

    <!-- Table to display selected items -->
    <table>
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

                    // Display the item in the table row
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

            // Handle removing the item
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

    <!-- Payment Button -->
    <form action="./views/payments.php" method="get" class="checkout">
        <input type="hidden" name="subTotal" value="sub total amount">
        <!-- When payment button is selected user will be directed to payments.php page -->
        <button type="submit">
            Confirm Order
        </button>
    </form>


</body>

</html>