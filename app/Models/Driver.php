<?php
/**
 * Driver Model
 */
class Driver extends Model
{
    protected static string $table = 'drivers';
    protected static array $fillable = [
        'name', 'email', 'phone', 'cpf', 'cnh',
        'vehicle_plate', 'vehicle_model', 'vehicle_color',
        'status', 'rating'
    ];

    /**
     * Get available drivers
     */
    public static function available(): array
    {
        return self::where(['status' => DRIVER_STATUS_AVAILABLE], 'name', 'ASC');
    }

    /**
     * Count active (non-offline) drivers
     */
    public static function countActive(): int
    {
        return (int) self::rawValue(
            "SELECT COUNT(*) FROM drivers WHERE status != :status",
            ['status' => DRIVER_STATUS_OFFLINE]
        );
    }

    /**
     * Count available drivers
     */
    public static function countAvailable(): int
    {
        return self::count(['status' => DRIVER_STATUS_AVAILABLE]);
    }

    /**
     * Search drivers with pagination
     */
    public static function search(int $page = 1, string $search = '', string $status = ''): array
    {
        $conditions = [];
        if ($status !== '') {
            $conditions['status'] = $status;
        }

        return self::paginate(
            $page,
            PER_PAGE,
            'created_at',
            'DESC',
            $conditions,
            $search,
            ['name', 'email', 'cpf', 'phone', 'vehicle_plate']
        );
    }

    /**
     * Get driver with ride count
     */
    public static function withRideCount(int $id): ?array
    {
        $driver = self::find($id);
        if (!$driver) return null;

        $driver['ride_count'] = (int) self::rawValue(
            "SELECT COUNT(*) FROM rides WHERE driver_id = :id",
            ['id' => $id]
        );
        $driver['completed_rides'] = (int) self::rawValue(
            "SELECT COUNT(*) FROM rides WHERE driver_id = :id AND status = 'completed'",
            ['id' => $id]
        );
        $driver['total_earnings'] = (float) self::rawValue(
            "SELECT COALESCE(SUM(fare), 0) FROM rides WHERE driver_id = :id AND status = 'completed'",
            ['id' => $id]
        );

        return $driver;
    }

    /**
     * Toggle driver status
     */
    public static function toggleStatus(int $id): ?string
    {
        $driver = self::find($id);
        if (!$driver) return null;

        $newStatus = match ($driver['status']) {
            DRIVER_STATUS_AVAILABLE => DRIVER_STATUS_OFFLINE,
            DRIVER_STATUS_OFFLINE => DRIVER_STATUS_AVAILABLE,
            DRIVER_STATUS_BUSY => DRIVER_STATUS_AVAILABLE,
            default => DRIVER_STATUS_OFFLINE,
        };

        self::update($id, ['status' => $newStatus]);
        return $newStatus;
    }
}
