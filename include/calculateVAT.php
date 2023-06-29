<?php

// The below function will calculate the included VAT onto the total amount for the items selected
function calculateVAT($PurchasedItemsTotal)
{

    $vatAmount = $PurchasedItemsTotal * 0.15;
    $vatInclusiveTotal = $PurchasedItemsTotal + $vatAmount;
    return $vatInclusiveTotal;

    return;
}
