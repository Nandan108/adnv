<?php

function getQueryLog(Callable $filter = null, $connection = 'default') {
    return null;
    // global $capsule;
    // $log = $capsule->getConnection($connection)->getQueryLog();
    // return $filter ? call_user_func($filter, $log) : $log;
}

function dbGetSelectStmt(
    string $sql,
    array|null $values=null
) {
    global $conn;

    $stmt = $conn->prepare($sql);
    $stmt->execute($values);
    return $stmt;
}

/**
 * Return all records from a query as arrays.
 * @param $values the values to be bound on execution
 * @param int $column If given will return that column's contents
 */
function dbGetAssoc($sql, $values = null) {
    $stmt = dbGetSelectStmt($sql, $values);
    switch ($stmt->columnCount()) {
        case 1:
            return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
        case 2:
            foreach ($stmt->fetchAll() as $line) {
                $output[$line[0]] = $line[1];
            }
            return $output;
        default:
            return $stmt->fetchAll();
    }};

/**
 * Return all records from a query as objects.
 * @param $values the values to be bound on execution
 * @param int $column If given will return that column's contents
 */
function dbGetAllObj($sql, $values = null, int $column = null) {
    $stmt = dbGetSelectStmt($sql, $values);
    if (isset($column)) {
        return $stmt->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_OBJ, $column);
    } else {
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
};

function dbGetOneVal(string $sql, array $values = [], $columnIdx = 0) {
    global $conn;
    $stmt = dbGetSelectStmt($sql, $values);
    return $stmt->fetchColumn($columnIdx);
}

function dbGetOneObj(string $sql, array $values = []) {
    global $conn;
    $stmt = dbGetSelectStmt($sql, $values);
    $obj = $stmt->fetch(\PDO::FETCH_OBJ);
    $meta = array_map(fn($i) => $stmt->getColumnMeta($i), range(0, $stmt->columnCount()));
    $stmt->closeCursor();
    return $obj;
}

/**
 * Execute an INSERT, UPDATE or DELETE statement
 * @param string $sql The SQL statement to be executed
 * @param array $values optional values to be substituted into the statement
 * @param mixed $error by-ref return of an error message, if there's a problem.
 * @param bool $transactionMode if TRUE, the execution will happen within a DB transaction.
 * @return mixed false on error, the ID of the latest inserted record in case of INSERT. TRUE for any other success.
 * @throws \PDOException
 */
function dbExec(string $sql, array $values = [], &$error = null, $transactionMode = false) {
    global $conn;
    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) die(debug_dump(['n\PDO::errorInfo()' => $conn->errorInfo(), 'SQL' => $sql]));

        // if a transaction mode is requested and we're currently in auto-commit mode,
        // try to start a transaction.
        $transStarted = ($transactionMode && dbGetOneVal('SELECT @@autocommit'))
            ? $conn->beginTransaction()
            : false;

        // execute SQL with given values for substitution
        $stmt->execute($values);

        // if this is an update statement, catch the latest INSERT ID to return it
        if (preg_match('/^\s*INSERT\s/i', $sql)) $id = $conn->lastInsertId();

        // if a transaction was requested, close it now
        if ($transStarted) $conn->commit();

        return $id ?? true;
    } catch (\Throwable $t) {
        $error = $t->getMessage();
        debug_dump(
            dumpData: ['error' => $t, 'statement' => $stmt, 'values' => $values],
            showStackLines: 5,
            stackLinesFilter: fn($l) => $l['file'] !== __FILE__
        );
        if ($transStarted) $conn->rollBack();
        return false;
    }
}

/**
 * Duplicates a record and returns the ID of the new record
 * @param string $ableName Name of the table
 * @param string $PK Name of the primary key field
 * @param string $sourceID ID of original record
 * @param callable $prepareRecord optional
 * @param string $titleField optional (field to modify by appending " (copy of $sourceID)")
 * @param string &$error in case of error, by-ref return of error message
 * @param bool $transactionMode
 * @return int|bool ID of new record (false in case of failure)
 */
function dbDuplicateRecord(
    string $tableName,
    string $PK,
    string $sourceID,
    callable $prepareRecord = null,
    string $titleField = null,
    &$error = null,
    $transactionMode = false
) {
    global $conn;

    $fields = implode(',', array_map(
        fn($field) => "`$field`",
        dbGetAssoc(
            "SELECT COLUMN_NAME
            FROM information_schema.columns c
                WHERE TABLE_SCHEMA = (SELECT DATABASE())
                AND TABLE_NAME = '$tableName'
                AND COLUMN_KEY != 'PRI'
                AND IS_GENERATED='NEVER'
        ")
    ));

    $record = dbGetOneObj("SELECT $fields FROM $tableName WHERE $PK = ?", [$sourceID]);

    if (!$record) {
        $error = 'Source record not found';
        return false;
    }

    $record = (array)($prepareRecord ? ($prepareRecord($record) ?: $record) : $record);
    if ($titleField) {
        $record[$titleField] .= " (copie de $sourceID)";
    }
    unset($record[$PK]);

    $recordFields = array_keys($record);
    $recordFieldsCSV = implode(', ', $recordFields);
    $valuePlaceholders = implode(',', array_fill_keys($recordFields, '?'));

    return dbExec(
        sql: "INSERT INTO $tableName ($recordFieldsCSV)\nVALUE ($valuePlaceholders)",
        values: array_values($record),
        error: $error,
        transactionMode: $transactionMode
    );
}
