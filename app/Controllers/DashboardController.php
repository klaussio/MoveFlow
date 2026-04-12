<?php
/**
 * DashboardController — Admin dashboard with metrics
 */
class DashboardController extends Controller
{
    /**
     * Dashboard page
     */
    public function index(): void
    {
        $this->requireAuth();

        $data = [
            'pageTitle' => 'Dashboard',
            'totalRides' => Ride::count(),
            'todayRides' => Ride::todayCount(),
            'ridesByStatus' => Ride::countByStatus(),
            'totalRevenue' => Ride::totalRevenue(),
            'monthlyRevenue' => Ride::monthlyRevenue(),
            'activeDrivers' => Driver::countActive(),
            'availableDrivers' => Driver::countAvailable(),
            'totalDrivers' => Driver::count(),
            'totalPassengers' => Passenger::count(),
            'recentRides' => Ride::recent(10),
            'revenuePerDay' => Ride::revenuePerDay(7),
            'statusDistribution' => Ride::statusDistribution(),
        ];

        $this->view('dashboard.index', $data);
    }

    /**
     * API endpoint for real-time stats (AJAX)
     */
    public function stats(): void
    {
        $this->requireAuth();

        $this->json([
            'success' => true,
            'data' => [
                'totalRides' => Ride::count(),
                'todayRides' => Ride::todayCount(),
                'inProgress' => Ride::count(['status' => RIDE_STATUS_IN_PROGRESS]),
                'requested' => Ride::count(['status' => RIDE_STATUS_REQUESTED]),
                'totalRevenue' => Ride::totalRevenue(),
                'monthlyRevenue' => Ride::monthlyRevenue(),
                'activeDrivers' => Driver::countActive(),
                'availableDrivers' => Driver::countAvailable(),
            ]
        ]);
    }
}
