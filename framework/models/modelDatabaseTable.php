<?php

/*
 * Database table base model.
 */


class ModelDatabaseTable {

    protected $tableName;
    protected $fields;
    private $interface;


    public function __construct($db, $tableName, $fields, $host, $user, $password,
                                $autoCreateTable=true, $defaultRecords=[]) {
        $this->interface = new ModelMySqlInterface($host, $user, $password);
        $this->tableName = "$db.$tableName";
        $this->fields = $fields;

        if ($autoCreateTable && !$this->checkIfTableExists()) {
            $this->createTable();
            foreach ($defaultRecords as $record) {
                $this->insertRecord($record);
            }
        }
    }


    public function getError() {
        return "SQL error {$this->interface->errno}: {$this->interface->error}";
    }


    public function checkIfTableExists() {
        return $this->interface->checkIfTableExists($this->tableName);
    }


    public function createTable() {
        $isSuccessful = $this->interface->createTable($this->tableName, $this->fields);
        if (!$isSuccessful) {
            $log = new ModelSystemLogger();
            $log->writeMessage("ERROR: table '{$this->tableName}' not created");
        }
        return $isSuccessful;
    }


    public function selectRecords($filterExpression="", $orderExpression="", $groupExpression="", $start=0, $count=0, $fields=["*"],
                                  $join="", $joinTable="", $joinExpression="", $joinFields=["*"]) {
        return $this->interface->selectRecordsFromTable($this->tableName, $filterExpression, $orderExpression, $groupExpression,
                                                        $start, $count, $fields, $join, $joinTable, $joinExpression, $joinFields);
    }


    public function selectRecordsFromQuery($query) {
        return $this->interface->selectRecordsFromQuery($query);
    }


    public function insertRecord($recordData) {
        return $this->interface->insertRecordIntoTable($this->tableName, $recordData);
    }


    public function updateRecord($newRecordData, $filterExpression) {
        return $this->interface->updateRecordFromTable($this->tableName, $newRecordData, $filterExpression);
    }


    public function deleteRecord($filterExpression) {
        return $this->interface->deleteRecordFromTable($this->tableName, $filterExpression);
    }

    public function getNumberOfRecords($filterExpression="") {
        return $this->interface->getRecordCount($this->tableName, $filterExpression);
    }


    public function checkIfRecordExist($recordData) {
        $recordExist = false;
        $filters = array();
        foreach($recordData as $field=>$value) {
            if (gettype($value) == "string") {
                $value = "'{$this->interface->sqlEscape($value)}'";
            }
            $filters[] = "$field = $value";
        }
        $result = $this->selectRecords(implode(" AND ", $filters));
        if ($result[0]) {
            $recordExist = count($result[1]) > 0;
        }
        return $recordExist;
    }


    public function clearTable() {
        return $this->interface->truncateTable($this->tableName);
    }

}

?>
