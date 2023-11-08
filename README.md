# SPDataTable

## Overview

The SPDataTable class is an editing tool that generates JQuery DataTable objects. Facilitates manipulation of an input  ``` data ```  before sending to the databable, for customization and flexibility. 


## Features

```addRow``` - Adds a new row to the databable

    $datatable = new SPDataTable();
    
    $datatable -> addRow(['address'] => 'state', 'zip' => 'areaLocation')


```editColumn``` - modifies the content of a specific column data --

    // Sample data for demonstration
    $addressData = [
        ['name' => 'John Doe', 'address' => '123 Main St'],
        ['name' => 'Jane Smith', 'address' => '456 Oak Ave'],
    ];

    // Initialize SPDataTable
    $dataTable = new SPDataTable();

    // Set data to the datatable
    $dataTable->setTableData($addressData);

    // Edit the 'address' column by appending "Street" to each address

    $dataTable->editColumn('address', function($value) {
        $editedValue = $value . " Street";
        return $editedValue;
    });


