<?php

class SPDataTable {
    private $data = [];
    private $columns = [];
    private $queryResult;

    public function addRow($row) {
        $this->data[] = $row;
        return $this;
    }

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

    public function removeRow($index) {
        if (isset($this->data[$index])) {
            unset($this->data[$index]);
            $this->data = array_values($this->data); // Reindex the array
        }
    }

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

    public function addColumnIfNotExists($columnName) {
        if (!in_array($columnName, $this->columns)) {
            $this->columns[] = $columnName;
            foreach ($this->data as &$row) {
                $row[$columnName] = ''; // Add an empty value for the new column
            }
        }
    }

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

    public function make() {
        $output = [
            'columns' => $this->columns,
            'data' => $this->data,
        ];

        return $this->data;
    }

    public function generateColumns($result) {
        if (!empty($result)) {
            $columns = [];
    
            foreach ($result as $row) {
                $columns = array_merge($columns, array_keys($row));
            }
    
            $this->columns = array_values(array_unique($columns));
        } 
    } 

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