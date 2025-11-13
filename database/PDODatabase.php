<?php



/**
 * Class PDODatabase
 * 
 * A lightweight PDO-based database abstraction layer for interacting with MySQL.
 * 
 * This class centralizes connection handling, query preparation, parameter binding,
 * and result fetching using PHP's PDO extension. It ensures secure database 
 * operations through prepared statements and exception handling.
 * 
 * @package Core\Database
 * @author  Amos Nyirenda
 * @version 1.0
 */
class PDODatabase {
    /**
     * @var PDO $handler The PDO connection handler.
     */
    private PDO $handler;

    /**
     * @var PDOStatement $stmt The prepared statement instance.
     */
    private $stmt;

    /**
     * Initializes a new PDO database connection using configuration constants.
     * 
     * The connection uses the following options:
     * - Persistent connections for better performance
     * - Exceptions for error reporting
     * - Associative array as default fetch mode
     * - Native prepared statements (no emulation)
     * 
     * @throws PDOException If the connection fails.
     */
    public function __construct() {
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->handler = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
                DB_USER,
                DB_PASS,
                $options
            );
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }


    /**
     * Starts a transaction.
     *
     * @return void
    */
    public function beginTransaction(): void {
        $this->handler->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * @return void
     */
    public function commit(): void {
        $this->handler->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * @return void
     */
    public function rollBack(): void {
        $this->handler->rollBack();
    }


    /**
     * Returns the ID of the last inserted row.
     *
     * @return string The last insert ID.
     */
    public function lastInsertId(): string {
        return $this->handler->lastInsertId();
    }







    /**
     * Prepares an SQL query for execution.
     * 
     * @param string $sql The SQL query string.
     * @return void
     */
    public function query(string $sql): void {
        $this->stmt = $this->handler->prepare($sql);
    }

    /**
     * Binds a value to a parameter in the prepared statement.
     * 
     * @param string $param The parameter identifier (e.g., ':id').
     * @param mixed $value The value to bind.
     * @param int $type The PDO data type (e.g., PDO::PARAM_INT, PDO::PARAM_STR).
     * @return void
     */
public function bindValue($param, $value, ?int $type = null): void {
    // Si le paramètre est numérique, il faut qu’il soit un entier, pas une chaîne
    if (is_numeric($param)) {
        $param = (int)$param;
    }
    $this->stmt->bindValue($param, $value, $type ?? PDO::PARAM_STR);
}


    /**
     * Executes the prepared SQL statement.
     * 
     * @return bool True on success, false on failure.
     */
    public function execute(): bool {
        return $this->stmt->execute();
    }

    /**
     * Executes the current statement and fetches a single result as an object.
     * 
     * @return object|null The resulting record as an object, or null if none found.
     */
    public function result() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Executes the current statement and fetches all results as an array of objects.
     * 
     * @return array The resulting records as an array of objects.
     */
    public function results(): array {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

?>
