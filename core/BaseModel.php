<?php

/**
 * Class BaseModel
 * 
 * Abstract base class for all database models in the Football Pitch Booking
 * Management System.
 * 
 * It defines the foundational structure and responsibilities of all models, 
 * such as managing the database connection, defining the table schema, 
 * and providing the standard CRUD (Create, Read, Update, Delete) operations.
 * 
 * Each subclass is responsible for:
 * - Defining its own table name and schema.
 * - Implementing methods for data access and manipulation.
 * 
 * This ensures a consistent design across all models, promotes code reuse, 
 * and enforces the use of PDO-based database interactions.
 * 
 * @package Core\BaseModel
 * @author  Amos Nyirenda
 * @version 1.0
 */
abstract class BaseModel
{
    /**
     * @var PDODatabase $db Database connection instance.
     */
    protected PDODatabase $db;

    /**
     * @var string $table Name of the database table used by the model.
     */
    protected string $table;

    /**
     * @var string $schema SQL schema definition for creating the table if it does not exist.
     */
    protected string $schema;

    /**
     * BaseModel constructor.
     * 
     * Initializes the database connection and ensures that
     * the model's table is properly set up by calling the `init()` method.
     * 
     * @param PDODatabase $database Database connection instance.
     */
    public function __construct(PDODatabase $database)
    {
        $this->db = $database;
        $this->init();
    }

    /**
     * Initializes the model by setting up its table name and schema,
     * and ensures that the table exists in the database.
     * 
     * This method is automatically called during construction and 
     * must be implemented by every subclass.
     * 
     * @return void
     */
    abstract protected function init(): void;

    /**
     * Retrieves all records from the model's table.
     * 
     * @return array List of all records as associative arrays.
     */
    abstract protected function getAll(): array;

    /**
     * Retrieves a single record from the model by its unique ID.
     * 
     * @param string $id The unique identifier of the record.
     * 
     * @return array|null The record data as an associative array, or null if not found.
     */
    abstract protected function getById(string $id): ?array;

    /**
     * Adds a new record to the database.
     * 
     * This method should use prepared statements for security.
     * 
     * @return bool True if the record was successfully added, false otherwise.
     */
    abstract protected function add(): bool;

    /**
     * Updates an existing record in the database.
     * 
     * @param string $id   The unique identifier of the record to update.
     * @param array  $data Key-value pairs of columns and their new values.
     * 
     * @return bool True if the update was successful, false otherwise.
     */
    abstract protected function update(string $id, array $data): bool;

    /**
     * Deletes a record from the database.
     * 
     * @param string $id The unique identifier of the record to delete.
     * 
     * @return bool True if the deletion was successful, false otherwise.
     */
    abstract protected function delete(string $id): bool;
}

?>
