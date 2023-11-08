<?php

namespace SP\DataTables;

/**
 * ------------------------------------------------------------------------------------
 * 
 *                                  SPDataTable Class
 * 
 *                  This class is used to edit and generate JQUERY 
 *                  datatable object. It is used to edit the data before 
 *                  sending it to the datatable.
 *                              
 * ------------------------------------------------------------------------------------
 */
class SPDataTable {
    private $data = [];
    private $columns = [];
    private $queryResult;

    /**
     * Add row function is used to add a row to the datatable.
     * 
     * @param array $row
     */
    public function addRow($row) {
        $this->data[] = $row;
        return $this;
    }
    

    /**
     * Edit column function is used to edit a column in the datatable. 
     * 
     * @param string $columnName
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
     * Remove row function is used to remove a row from the datatable.
     * 
     * @param int $index
     */
    public function removeRow($index) {
        if (isset($this->data[$index])) {
            unset($this->data[$index]);
            $this->data = array_values($this->data); // Reindex the array
        }
    }

    /**
     * Remove column function is used to remove a column from the datatable.
     * 
     * @param string $columnName
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
     * Add column function is used to add a column to the datatable if it does not exists.
     * 
     * @param string $columnName
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
     * Set custom content function is used to set a new custom column content.
     * 
     * @param string $columnName
     * @param callable $contentCallback
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
     * Make function is used to generate the datatable parsed content.
     * 
     * @return array
     */
    public function make() {
        $output = [
            'columns' => $this->columns,
            'data' => $this->data,
        ];

        return $this->data;
    }

    /**
     * Generate columns function is used to generate the columns of the datatable 
     * for mapping purposes.
     * 
     * @param array $result
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
     * Set table data function is used to set the data of the datatable.
     * 
     * @param array $queryResult
     * @throws \Exception
     * @return SPDataTable
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