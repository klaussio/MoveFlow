<?php
/**
 * Passenger Model
 */
class Passenger extends Model
{
    protected static string $table = 'passengers';
    protected static array $fillable = ['name', 'email', 'phone', 'cpf'];

    /**
     * Search passengers with pagination
     */
    public static function search(int $page = 1, string $search = ''): array
    {
        return self::paginate(
            $page,
            PER_PAGE,
            'created_at',
            'DESC',
            [],
            $search,
            ['name', 'email', 'cpf', 'phone']
        );
    }

    /**
     * Get passenger with ride count
     */
    public static function withRideCount(int $id): ?array
    {
        $passenger = self::find($id);
        if (!$passenger) return null;

        $passenger['ride_count'] = (int) self::rawValue(
            "SELECT COUNT(*) FROM rides WHERE passenger_id = :id",
            ['id' => $id]
        );
        $passenger['total_spent'] = (float) self::rawValue(
            "SELECT COALESCE(SUM(fare), 0) FROM rides WHERE passenger_id = :id AND status = 'completed'",
            ['id' => $id]
        );

        return $passenger;
    }

    /**
     * Get all passengers for dropdown
     */
    public static function allForSelect(): array
    {
        return self::raw("SELECT id, name, phone FROM passengers ORDER BY name ASC");
    }
}
