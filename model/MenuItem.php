<?php

class MenuItem
{
    // properties:
    private $name;
    private $price;
    private $barcode;

    // Constructor
    // The constructor allows  to set the initial values of the MenuItem object properties
    public function __construct($name, $price, $barcode)
    {
        $this->name = $name;
        $this->price = $price;
        $this->barcode = $barcode;
    }

    // Getters and Setters:
    // The getters and setters enables you to access and modify the property values.

    // getName
    public function getName()
    {
        return $this->name;
    }

    // setName
    public function setName($name)
    {
        $this->name = $name;
    }

    // getPrice
    public function getPrice()
    {
        return $this->price;
    }

    // setPrice
    public function setPrice($price)
    {
        $this->price = $price;
    }

    // getBarcode
    public function getBarcode()
    {
        return $this->barcode;
    }

    // setBarcode
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    // methods
    // The loadData() method loads data from the data.php file, creates MenuItem objects using the provided data, and returns an array of MenuItem objects.
    public static function loadData()
    {
        include 'data/data.php';

        $menuItems = array();

        foreach ($items as $item) {
            $menuItem = new MenuItem(
                $item['name'],
                $item['price'],
                $item['barcode']
            );

            $menuItems[] = $menuItem;
        }

        return $menuItems;
    }
}
