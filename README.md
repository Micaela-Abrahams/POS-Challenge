# POS-Challenge

The purpose of the Point of Sale System is for the user to select items from the provided list & add them to the order.

The POS System will take the user through 3 pages:

1. Index Page [index.php]
2. Payment page [payments.php]
3. Confirmation page [confirmation.php]

### Index.php
When the user selects an item, the items selcted will get added to a table, this table will display the item name, the cost & the barcode number.
As the number of items that gets added order list, the till display amount increses as well - displaying the calculated cost for the total cost of the items.

On the order list the user has the option to "Remove" an item from the order list. When an item is removed from the order list, the till display amount will decrease displaying the now updated total cost for the selected items that remain in the cart.

Once the user is satified with their order, they will click "Confirm order". the user will now be redirected to a new page - payments.php.

### Payments.php
On this page the items the user has confirmed for their items list will redislay, the only thing that will appear different in the table is that the remove button has been excluded. Should the user wish to remove an item at this stage they will need to go back, with the provided back button, to remove an item from the list.

Furthermore, the total cost of the items will be displayed, as well as the VAT and the subtotal amount for the entire cost of the users list.
The user will have an option to choose their payment prefernce - Card or Card. 
When the user selects the Cash or Card buttons they will be redirected to the confirmation.php page.

### Confirmation.php
On the confirmation page displayed will be a message confirming the successful cash/card payment.
Below this message will be a button which will return user to the index.php page.

## Technologies used:

* _PHP_
* _HTML_
* _CSS_

## Authors

- [@Micaela-Abrahams](https://github.com/Micaela-Abrahams)

## Class Code 
pt2210
