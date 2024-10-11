<?php

/*
 * Interface model for MySQL databases.
 */


class ModelMySqlInterface extends mysqli {

    public function __construct($host, $user, $password) {
        parent::__construct($host, $user, $password);
        if (mysqli_connect_errno() > 0) {
            $log = new ModelSystemLogger();
            $log->writeMessage(mysqli_connect_error());
        }
    }


    public function __destruct() {
        if ($this->isConnected()) {
            $this->close();
        }
    }


    public function isExecutionSuccessful($extraMessage) {
        if ($this->errno > 0) {
            $log = new ModelSystemLogger();
            $log->writeMessage($this->error);
            $log->writeMessage($extraMessage);
        }
        return ($this->errno == 0);
    }


    public function isConnected() {
        return mysqli_connect_errno() == 0;
    }


    public function getLastError() {
        return mysqli_connect_error();
    }


    public function checkIfTableExists($tableName) {
        $parts = explode(".", $tableName);
        $query = "SHOW TABLES FROM {$parts[0]} LIKE '" . end($parts) . "'";
        $result = $this->selectRecordsFromQuery($query);
        return ($result[0] && (count($result[1]) > 0));
    }


    public function createTable($tableName, $fields) {
        $query = "CREATE TABLE $tableName (\n";
        $lines = array();
        $keys = array();
        foreach ($fields as $field) {
            $line = "{$field["Name"]} {$field["Type"]}";
            if (isset($field["Options"])) {
                $line .= " {$field["Options"]}";
            }
            if (isset($field["Key"]))
            {
                if ($field["Key"]) {
                    $keys[] = $field["Name"];
                }
            }
            $lines[] = $line;
        }
        $query .= implode(",\n", $lines);
        if (count($keys) > 0) {
            $query .= ",\nPRIMARY KEY (" . implode(", ", $keys) . ")";
        }
        $query .= "\n)";
        $this->query($query);
        return $this->isExecutionSuccessful($query);
    }


    public function insertRecordIntoTable($tableName, $recordData) {
        $query = "INSERT INTO $tableName (";
        $columns = array();
        $values = array();
        foreach ($recordData as $field => $value) {
            $columns[] = $field;
            if (gettype($value) == "string" && $value != "NULL") {
                $value = "'{$this->sqlEscape($value)}'";
            }
            $values[] = $value;
        }
        $query .= join(", ", $columns) . ") VALUES (";
        $query .= join(", ", $values) . ")";
        $this->query($query);
        return $this->isExecutionSuccessful($query);
    }


    public function selectRecordsFromTable($tableName, $filterExpression="", $orderExpression="",
                                           $groupExpression="", $start=0, $count=0, $fields=["*"],
                                           $join="", $joinTable="", $joinExpression="", $joinFields=["*"]) {
        $records = [];
        $selectFields = $fields;
        if ($join != "") {
            $selectFields = array_map(function($x){
                return "a.$x";
            }, $fields);
            $selectFields = array_merge($selectFields, array_map(function($x){
                return "b.$x";
            }, $joinFields));  
        }
        $query = "SELECT " . implode(", ", $selectFields) . " FROM $tableName";
        if ($join != "") {
            $joinExpression = str_replace(" = ", " = b.", "a.$joinExpression");
            $query .= " a $join JOIN $joinTable b ON $joinExpression";
        }
        if ($filterExpression != "") {
            if ($join != "") {
                $filterExpression = "a.$filterExpression";
            }
            $query .= " WHERE $filterExpression";
        }
        if ($groupExpression != "") {
            if ($join != "") {
                $groupExpression = "a.$groupExpression";
            }
            $query .= " GROUP BY $groupExpression";
        }
        if ($orderExpression != "") {
            if ($join != "") {
                $orderExpression = "a.$orderExpression";
            }
            $query .= " ORDER BY $orderExpression";
        }
        if ($count > 0 && $start >= 0) {
            $query .= " LIMIT $start, $count";
        }
        return $this->selectRecordsFromQuery($query);
    }


    public function selectRecordsFromQuery($query) {
        $records = array();
        $result = $this->query($query);
        if ($result !== false) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
        }
        return array($this->isExecutionSuccessful($query), $records);
    }


    public function updateRecordFromTable($tableName, $newRecordData, $filterExpression) {
        $query = "UPDATE $tableName SET ";
        $fieldUpdates = array();
        foreach ($newRecordData as $key => $value) {
            if (gettype($value) == "string" && $value != "NULL") {
                $value = "'{$this->sqlEscape($value)}'";
            }
            $fieldUpdates[] = "$key = $value";
        }
        $query .= join(", ", $fieldUpdates);
        $query .= " WHERE $filterExpression";
        $this->query($query);
        return $this->isExecutionSuccessful($query);
    }


    public function deleteRecordFromTable($tableName, $filterExpression) {
        $query = "DELETE FROM $tableName WHERE $filterExpression";
        $this->query($query);
        return $this->isExecutionSuccessful($query);
    }


    public function getRecordCount($tableName, $filterExpression="") {
        $nRecords = 0;
        $query = "SELECT COUNT(*) AS NumberOfRecords FROM $tableName";
        if ($filterExpression != "") {
            $query .= " WHERE $filterExpression";
        }
        $result = $this->selectRecordsFromQuery($query);
        if ($result[0]) {
            if (count($result[1]) > 0) {
                if (isset($result[1][0]["NumberOfRecords"])) {
                    $nRecords = intval($result[1][0]["NumberOfRecords"]);
                }
            }
        }
        return $nRecords;
    }


    public function truncateTable($tableName) {
        $query = "TRUNCATE $tableName";
        $this->query($query);
        return $this->isExecutionSuccessful($query);
    }


    public function dropTable($tableName) {
        $query = "DROP TABLE $tableName";
        $this->query($query);
        return $this->isExecutionSuccessful($query);
    }


    public function sqlEscape($text)
    {
        $textOut = str_replace("\\", "\\\\", $text);
        return str_replace("'", "\'", $textOut);
    }

}

?>
