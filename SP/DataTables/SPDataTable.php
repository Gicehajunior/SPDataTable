<?php

namespace SP\DataTables;

/**
 * -------------------------------------------------------------------------
 * 
 *                   SPDataTable Class: Data Modification for jQuery Datatables
 * 
 * This class is dedicated to editing and crafting jQuery datatable objects,
 * providing a means to adjust data before it is transmitted to the datatable.
 *                              
 * -------------------------------------------------------------------------
 */
class SPDataTable {
    /**
     * @var array Holds the data rows for the datatable.
     */
    private $data = [];

    /**
     * @var array Holds the columns of the datatable.
     */
    private $columns = [];

    /**
     * @var array Holds the original query result data.
     */
    private $queryResult;

    /**
     * Adds a row to the datatable.
     * 
     * @param array $row The data row to be added.
     * @return SPDataTable Returns the instance for method chaining.
     */
    public function addRow($row) {
        $this->data[] = $row;
        return $this;
    }

    /**
     * Edits a column in the datatable.
     * 
     * @param string $columnName The name of the column to be edited.
     * @param callable $editCallback The callback function to edit the column content.
     * @return SPDataTable Returns the instance for method chaining.
     */
    public function editColumn($columnName, $editCallback) {
        $columnIndex = array_search($columnName, $this->columns, true);

        if ($columnIndex !== false) {
            foreach ($this->data as &$row) {
                if (isset($row[$columnIndex])) {
                    $row[$columnIndex] = $editCallback($row[$columnIndex]);
                }
            }
        }

        return $this; // Return the instance for method chaining
    }

    /**
     * Removes a row from the datatable.
     * 
     * @param int $index The index of the row to be removed.
     */
    public function removeRow($index) {
        if (isset($this->data[$index])) {
            unset($this->data[$index]);
            $this->data = array_values($this->data); // Reindex the array
        }
    }

    /**
     * Removes a column from the datatable.
     * 
     * @param string $columnName The name of the column to be removed.
     * @return SPDataTable Returns the instance for method chaining.
     */
    public function removeColumn($columnName) {
        $columnIndexes = array_keys($this->columns, $columnName);
    
        foreach ($columnIndexes as $columnIndex) {
            unset($this->columns[$columnIndex]);
            foreach ($this->data as &$row) {
                unset($row[$columnName]);
            }
        } 
    
        return $this;
    }

    /**
     * Adds a column to the datatable if it does not exist.
     * 
     * @param string $columnName The name of the column to be added.
     */
    public function addColumnIfNotExists($columnName) {
        if (!in_array($columnName, $this->columns)) {
            $this->columns[] = $columnName;
            foreach ($this->data as &$row) {
                $row[$columnName] = ''; // Add an empty value for the new column
            }
        }
    }

    /**
     * Sets new custom column content.
     * 
     * @param string $columnName The name of the column.
     * @param callable $contentCallback The callback function to set custom column content.
     * @return SPDataTable Returns the instance for method chaining.
     */
    public function setCustomContent($columnName, $contentCallback) {
        $this->addColumnIfNotExists($columnName);

        $columnIndex = array_search($columnName, $this->columns, true);

        if ($columnIndex !== false) {
            foreach ($this->data as &$row) {
                $row[$columnName] = $contentCallback($row);
            }
        }

        return $this;
    } 

    /**
     * Generates the datatable parsed content.
     * 
     * @return array The datatable content.
     */
    public function make() {
        $output = [
            'columns' => $this->columns,
            'data' => $this->data,
        ];

        return $this->data;
    }

    /**
     * Generates the columns of the datatable for mapping purposes.
     * 
     * @param array $result The query result data.
     */
    public function generateColumns($result) {
        if (!empty($result)) {
            $columns = [];
    
            foreach ($result as $row) {
                $columns = array_merge($columns, array_keys($row));
            }
    
            $this->columns = array_values(array_unique($columns));
        } 
    } 

    /**
     * Sets the data of the datatable.
     * 
     * @param array $queryResult The query result data.
     * @throws \Exception If data expected to be parsed is not found.
     * @return SPDataTable Returns the instance for method chaining.
     */
    public function setTableData($queryResult) { 
        if (!empty($queryResult)) {
            $this->queryResult = $queryResult;
            $this->generateColumns($this->queryResult);

            foreach ($this->queryResult as $key => $row) {
                $this->addRow($row);
            }
        } else {
            throw new \Exception("Data expected to be parsed not found!");
        }

        return $this;
    }
}
