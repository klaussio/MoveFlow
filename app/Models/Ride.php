<?php
/**
 * Ride Model — Full ride lifecycle management
 */
class Ride extends Model
{
    protected static string $table = 'rides';
    protected static array $fillable = [
        'passenger_id', 'driver_id',
        'origin_address', 'origin_lat', 'origin_lng',
        'destination_address', 'destination_lat', 'destination_lng',
        'status', 'distance_km', 'estimated_duration_min', 'fare',
        'requested_at', 'accepted_at', 'started_at', 'completed_at',
        'cancelled_at', 'cancel_reason'
    ];

    /**
     * Calculate fare based on distance and duration
     */
    public static function calculateFare(float $distanceKm, int $durationMin): float
    {
        $fare = FARE_BASE + ($distanceKm * FARE_PER_KM) + ($durationMin * FARE_PER_MIN);
        return round($fare, 2);
    }

    /**
     * Search rides with pagination and filters
     */
    public static function search(int $page = 1, string $search = '', string $status = '', string $dateFrom = '', string $dateTo = ''): array
    {
        $conditions = [];
        $params = [];
        $whereClauses = [];

        if ($status !== '') {
            $whereClauses[] = "r.status = :status";
            $params['status'] = $status;
        }

        if ($dateFrom !== '') {
            $whereClauses[] = "r.requested_at >= :date_from";
            $params['date_from'] = $dateFrom . ' 00:00:00';
        }

        if ($dateTo !== '') {
            $whereClauses[] = "r.requested_at <= :date_to";
            $params['date_to'] = $dateTo . ' 23:59:59';
        }

        if ($search !== '') {
            $whereClauses[] = "(p.name LIKE :search OR d.name LIKE :search2 OR r.origin_address LIKE :search3 OR r.destination_address LIKE :search4)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
            $params['search4'] = "%{$search}%";
        }

        $whereSQL = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';
        $perPage = PER_PAGE;
        $offset = ($page - 1) * $perPage;

        // Count total
        $countSQL = "SELECT COUNT(*) FROM rides r
                     LEFT JOIN passengers p ON r.passenger_id = p.id
                     LEFT JOIN drivers d ON r.driver_id = d.id"
                     . $whereSQL;
        $stmt = self::db()->prepare($countSQL);
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        // Fetch with joins
        $sql = "SELECT r.*, p.name as passenger_name, p.phone as passenger_phone,
                       d.name as driver_name, d.vehicle_model, d.vehicle_plate
                FROM rides r
                LEFT JOIN passengers p ON r.passenger_id = p.id
                LEFT JOIN drivers d ON r.driver_id = d.id"
                . $whereSQL
                . " ORDER BY r.created_at DESC LIMIT {$perPage} OFFSET {$offset}";

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
     * Get ride with full details (joins)
     */
    public static function findWithDetails(int $id): ?array
    {
        $sql = "SELECT r.*, p.name as passenger_name, p.phone as passenger_phone, p.email as passenger_email,
                       d.name as driver_name, d.phone as driver_phone, d.vehicle_model, d.vehicle_plate, d.vehicle_color, d.rating as driver_rating
                FROM rides r
                LEFT JOIN passengers p ON r.passenger_id = p.id
                LEFT JOIN drivers d ON r.driver_id = d.id
                WHERE r.id = :id LIMIT 1";

        $result = self::raw($sql, ['id' => $id]);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Get recent rides
     */
    public static function recent(int $limit = 10): array
    {
        $sql = "SELECT r.*, p.name as passenger_name, d.name as driver_name
                FROM rides r
                LEFT JOIN passengers p ON r.passenger_id = p.id
                LEFT JOIN drivers d ON r.driver_id = d.id
                ORDER BY r.created_at DESC LIMIT {$limit}";

        return self::raw($sql);
    }

    /**
     * Count rides by status
     */
    public static function countByStatus(): array
    {
        $sql = "SELECT status, COUNT(*) as total FROM rides GROUP BY status";
        $results = self::raw($sql);
        $counts = [];
        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['total'];
        }
        return $counts;
    }

    /**
     * Total revenue
     */
    public static function totalRevenue(): float
    {
        return (float) self::rawValue(
            "SELECT COALESCE(SUM(fare), 0) FROM rides WHERE status = 'completed'"
        );
    }

    /**
     * Revenue for current month
     */
    public static function monthlyRevenue(): float
    {
        return (float) self::rawValue(
            "SELECT COALESCE(SUM(fare), 0) FROM rides WHERE status = 'completed' AND MONTH(completed_at) = MONTH(CURRENT_DATE()) AND YEAR(completed_at) = YEAR(CURRENT_DATE())"
        );
    }

    /**
     * Today's ride count
     */
    public static function todayCount(): int
    {
        return (int) self::rawValue(
            "SELECT COUNT(*) FROM rides WHERE DATE(created_at) = CURDATE()"
        );
    }

    /**
     * Revenue per day for chart (last 7 days)
     */
    public static function revenuePerDay(int $days = 7): array
    {
        $sql = "SELECT DATE(completed_at) as date, SUM(fare) as revenue, COUNT(*) as rides
                FROM rides
                WHERE status = 'completed' AND completed_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE(completed_at)
                ORDER BY date ASC";

        return self::raw($sql, ['days' => $days]);
    }

    /**
     * Rides per status for chart
     */
    public static function statusDistribution(): array
    {
        $sql = "SELECT status, COUNT(*) as total FROM rides GROUP BY status ORDER BY total DESC";
        return self::raw($sql);
    }
}
