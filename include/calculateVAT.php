<?php

function calculateVAT($PurchasedItemsTotal)
{

    $vatAmount = $PurchasedItemsTotal * 0.15;
    $vatInclusiveTotal = $PurchasedItemsTotal + $vatAmount;
    return $vatInclusiveTotal;

    return;
}
