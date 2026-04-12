<?php
/**
 * Base Model — Simplified query builder for MySQL
 */
class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];

    /**
     * Get PDO connection
     */
    protected static function db(): PDO
    {
        return Database::getInstance();
    }

    /**
     * Find a record by ID
     */
    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Find all records with optional ordering
     */
    public static function findAll(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $stmt = self::db()->query(
            "SELECT * FROM " . static::$table . " ORDER BY {$orderBy} {$direction}"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find records with conditions
     */
    public static function where(array $conditions, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $field => $value) {
            $whereClauses[] = "{$field} = :{$field}";
            $params[$field] = $value;
        }

        $sql = "SELECT * FROM " . static::$table
            . " WHERE " . implode(' AND ', $whereClauses)
            . " ORDER BY {$orderBy} {$direction}";

        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new record
     */
    public static function create(array $data): int
    {
        $filtered = self::filterFillable($data);
        $columns = implode(', ', array_keys($filtered));
        $placeholders = ':' . implode(', :', array_keys($filtered));

        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = self::db()->prepare($sql);
        $stmt->execute($filtered);

        return (int) self::db()->lastInsertId();
    }

    /**
     * Update a record by ID
     */
    public static function update(int $id, array $data): bool
    {
        $filtered = self::filterFillable($data);
        $setClauses = [];

        foreach ($filtered as $field => $value) {
            $setClauses[] = "{$field} = :{$field}";
        }

        $sql = "UPDATE " . static::$table
            . " SET " . implode(', ', $setClauses)
            . " WHERE " . static::$primaryKey . " = :_id";

        $filtered['_id'] = $id;
        $stmt = self::db()->prepare($sql);
        return $stmt->execute($filtered);
    }

    /**
     * Delete a record by ID
     */
    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare(
            "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count all records or with conditions
     */
    public static function count(array $conditions = []): int
    {
        if (empty($conditions)) {
            $stmt = self::db()->query("SELECT COUNT(*) FROM " . static::$table);
            return (int) $stmt->fetchColumn();
        }

        $whereClauses = [];
        $params = [];
        foreach ($conditions as $field => $value) {
            $whereClauses[] = "{$field} = :{$field}";
            $params[$field] = $value;
        }

        $sql = "SELECT COUNT(*) FROM " . static::$table . " WHERE " . implode(' AND ', $whereClauses);
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Paginate results
     */
    public static function paginate(int $page = 1, int $perPage = 15, string $orderBy = 'id', string $direction = 'DESC', array $conditions = [], string $search = '', array $searchFields = []): array
    {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereClauses = [];

        // Apply conditions
        foreach ($conditions as $field => $value) {
            $whereClauses[] = "{$field} = :{$field}";
            $params[$field] = $value;
        }

        // Apply search
        if ($search !== '' && !empty($searchFields)) {
            $searchClauses = [];
            foreach ($searchFields as $i => $field) {
                $searchClauses[] = "{$field} LIKE :search_{$i}";
                $params["search_{$i}"] = "%{$search}%";
            }
            $whereClauses[] = '(' . implode(' OR ', $searchClauses) . ')';
        }

        $whereSQL = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

        // Count total
        $countSQL = "SELECT COUNT(*) FROM " . static::$table . $whereSQL;
        $stmt = self::db()->prepare($countSQL);
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        // Fetch records
        $sql = "SELECT * FROM " . static::$table . $whereSQL
            . " ORDER BY {$orderBy} {$direction}"
            . " LIMIT {$perPage} OFFSET {$offset}";

        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $records,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }

    /**
     * Execute a raw query
     */
    public static function raw(string $sql, array $params = []): array
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Execute a raw query and return single value
     */
    public static function rawValue(string $sql, array $params = [])
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Filter data to only include fillable fields
     */
    private static function filterFillable(array $data): array
    {
        if (empty(static::$fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip(static::$fillable));
    }
}
