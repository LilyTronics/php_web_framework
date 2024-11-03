<?php

/*
 * Database table base model.
 */


class ModelDatabaseTable
{

    protected $database = "unknown";
    protected $tableName = "unknown";
    protected $fields = [];

    private $interface;
    private $databaseTable;

    public function __construct($host, $user, $password, $autoCreateTable=true, $defaultRecords=[])
    {
        $this->interface = new ModelMySqlInterface($host, $user, $password);

        if ($autoCreateTable && !$this->checkIfTableExists())
        {
            $this->createTable();
            foreach ($defaultRecords as $record)
            {
                $this->insertRecord($record);
            }
        }
        $this->databaseTable = "{$this->database}.{$this->tableName}";
    }


    public function getError()
    {
        return "SQL error {$this->interface->errno}: {$this->interface->error}";
    }


    public function checkIfTableExists()
    {
        return $this->interface->checkIfTableExists($this->database, $this->tableName);
    }


    public function createTable()
    {
        $isSuccessful = $this->interface->createTable($this->databaseTable, $this->fields);
        if (!$isSuccessful)
        {
            $log = new ModelSystemLogger();
            $log->writeMessage("ERROR: table '{$this->tableName}' not created");
        }
        return $isSuccessful;
    }


    public function selectRecords($filterExpression="", $orderExpression="", $groupExpression="", $start=0, $count=0, $fields=["*"],
                                  $join="", $joinTable="", $joinExpression="", $joinFields=["*"])
    {
        return $this->interface->selectRecordsFromTable($this->databaseTable, $filterExpression, $orderExpression, $groupExpression,
                                                        $start, $count, $fields, $join, $joinTable, $joinExpression, $joinFields);
    }


    public function selectRecordsFromQuery($query)
    {
        return $this->interface->selectRecordsFromQuery($query);
    }


    public function insertRecord($recordData)
    {
        return $this->interface->insertRecordIntoTable($this->databaseTable, $recordData);
    }


    public function updateRecord($newRecordData, $filterExpression)
    {
        return $this->interface->updateRecordFromTable($this->databaseTable, $newRecordData, $filterExpression);
    }


    public function deleteRecord($filterExpression)
    {
        return $this->interface->deleteRecordFromTable($this->databaseTable, $filterExpression);
    }

    public function getNumberOfRecords($filterExpression="")
    {
        return $this->interface->getRecordCount($this->databaseTable, $filterExpression);
    }


    public function checkIfRecordExist($recordData)
    {
        $recordExist = false;
        $filters = array();
        foreach($recordData as $field=>$value)
        {
            if (gettype($value) == "string")
            {
                $value = "'{$this->interface->sqlEscape($value)}'";
            }
            $filters[] = "$field = $value";
        }
        $result = $this->selectRecords(implode(" AND ", $filters));
        if ($result[0])
        {
            $recordExist = count($result[1]) > 0;
        }
        return $recordExist;
    }

    public function executeQuery($query)
    {
        return $this->interface->query($query);
    }

    public function clearTable()
    {
        return $this->interface->truncateTable($this->databaseTable);
    }

    public function deleteTable()
    {
        return $this->interface->dropTable($this->databaseTable);
    }

}

?>
