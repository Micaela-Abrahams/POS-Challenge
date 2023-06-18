<?php
// The below function will add up the cost of all the items the user has selected 
function getItemPrice($itemName)
{
    // Include the $items array from data.php
    include 'data/data.php'; // Data from the data.php page is included on the index.php page

    // Search for the item name in the array & return the price of the item
    foreach ($items as $item) {
        if ($item['name'] == $itemName) {
            return $item['price'];
        }
    }

    // If no array item is found, return 0
    // return 0;
}
