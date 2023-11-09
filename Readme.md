# SPDataTable

SPDataTable is a simple jQuery datatable editor library in PHP that allows you to manipulate and generate data tables effortlessly. It has a simple and easy to use API that allows you to add, edit, remove and generate data tables from a query result or an array of data. It supports SelfPhP, CodeIgnitor, Cake PHP, Laravel and other PHP frameworks as well as procedural PHP. Below is the walkthrough of how to use SPDataTable.

## Installation

You can install SPDataTable by downloading the class SP\Datatables\SPDataTable.php and including it in your project. You can also install it using Composer as shown below:

```bash
composer require sp-datatables/sp-datatables
```

# Usage
## Basic Usage

```php 

require __DIR__ . "/SPDataTable.php";

// Create an instance of SPDataTable
$dataTable = new SPDataTable();

// Add data rows
$dataTable->addRow(['name' => 'John', 'age' => 25, 'city' => 'New York']);
$dataTable->addRow(['name' => 'Jane', 'age' => 30, 'city' => 'London']);

// Edit a column
$dataTable->editColumn('age', function($value) {
    return $value * 2;
});

```

To generate the columns and data, call the `make()` method.

```php
// Generate columns and data
$result = $dataTable->make();
``` 

```php
// Output the result
print_r($result);
```

## Advanced Usage

```php 

require_once 'vendor/autoload.php';

use SP\DataTables\SPDataTable;

// Create an instance of SPDataTable
$dataTable = new SPDataTable();

// Set table data from a query result
$queryResult = [
    ['id' => 1, 'name' => 'John', 'age' => 25, 'city' => 'New York'],
    ['id' => 2, 'name' => 'Jane', 'age' => 30, 'city' => 'London'],
    // ... more data
];

$dataTable->setTableData($queryResult)
    ->editColumn('age', function($value) {
        return $value * 2;
    })
    ->removeRow(1)
    ->removeColumn('id')
    ->setCustomContent('custom', function($row) {
        return $row['name'] . ' lives in ' . $row['city'];
    });

```

```php
// Generate columns and data
$result = $dataTable->make();
```

```php
// Output the result
print_r($result);
``` 

### Output Example
The output will be in JSON format.

```json

{
    [
        {
            "name": "John",
            "age": 50,
            "city": "New York",
            "custom": "John lives in New York"
        },
        {
            "name": "Jane",
            "age": 60,
            "city": "London",
            "custom": "Jane lives in London"
        }
    ]
}

```

## Documentation
For more information, please raise an issue or contact me at [Giceha Junior](mailto:gicehajunior76@gmail.com)

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change. Please make sure to update tests as appropriate. 

## License
License, [MIT](https://github.com/Gicehajunior/SPDataTable/blob/main/LICENSE)