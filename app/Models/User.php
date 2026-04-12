<?php
/**
 * User Model — Admin users
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = ['name', 'email', 'password_hash', 'role'];

    /**
     * Find user by email
     */
    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Verify password
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash password
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
