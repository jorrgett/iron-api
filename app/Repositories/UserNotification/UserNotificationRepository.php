<?php

namespace App\Repositories\UserNotification;

use App\Models\Notification;
use App\Models\UserNotification;
use App\Repositories\BaseInterface;
use Illuminate\Support\Facades\DB;


class UserNotificationRepository implements BaseInterface
{
    protected $model;
    protected $user_notification;

    /**
     * User Notification Constructor.
     * @param UserNotification $user_notification
     */
    public function __construct(UserNotification $user_notification)
    {
        $this->model = $user_notification;
    }

    /**
     * Get all paginated records
     * 
     * @param $data
     * @return UserNotificationCollection
     */
    public function getAll($data)
    {
        $allowedFilters = ['user_id', 'notification_id', 'status', 'topic_1', 'topic_2', 'sent_date', 'read_date'];

        $filters = array_filter($data, function ($value, $key) use ($allowedFilters) {
            return in_array($key, $allowedFilters) && $value !== null;
        }, ARRAY_FILTER_USE_BOTH);

        $query = $this->model::with(['user', 'notification', 'vehicle'])
            ->when(!empty($data['sent_date']), fn($q) => $q->whereDate('sent_date', $data['sent_date']))
            ->when(!empty($data['read_date']), fn($q) => $q->whereDate('read_date', $data['read_date']));

        foreach ($filters as $field => $value) {
            if (!in_array($field, ['sent_date', 'read_date'])) {
                $query->where($field, $value);
            }
        }

        $pageSize = !empty($data['size']) ? (int) $data['size'] : 10;
        return $query->paginate($pageSize);
    }

    /**
     * Store a newly created record in storage
     * 
     * @param array $data
     * @return UserNotification
     */
    public function create(array $data): UserNotification
    {
        $notification = Notification::find($data['notification_id']);

        $defaultData = [
            'topic_1' => $notification->name,
            'topic_2' => $notification->type,
        ];
    
        $data = array_merge($defaultData, $data);
    
        $user_notification = new UserNotification();
        $user_notification->fill($data);
        $user_notification->save();
    
        return $user_notification;
    }

    /**
     * Display the specified record by field.
     * 
     * @param string $field
     * @param mixed $value
     * @param string $operator
     * @return UserNotification|null
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified record in storage
     * 
     * @param int $id
     * @param bool|null
     * @throws \Exception
     */
    public function destroy($id) {}

    /**
     * Update the specified record in storage
     * 
     * @param int $id
     * @param array $data
     * @return UserNotification|null
     */
    public function UpdateById($id, array $data)
    {
        $user_notification = $this->getByField('id', $id);

        if (!$user_notification) {
            return null;
        }

        $user_notification->status = $data['status'];
        $user_notification->read_date = now();

        $user_notification->save();

        return $user_notification;
    }

    /**
     * Get All Notifications By User
     */
    public function getByUser($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence']) ? (int)$data['sequence'] : 0;

        return $this->model::with(['user', 'notification', 'vehicle'])
            ->where('user_id', auth()->user()->id)
            ->where('id', '>', $sequence)
            ->paginate($page);
    }

    /**
     * Get Users By Notification
     */
    public function getByNotification($data)
    {
        $id = !empty($data['id']) ? (int)$data['id'] : null;

        return $this->model::with(['user', 'notification', 'vehicle'])
            ->where('notification_id', $id)
            ->get();
    }

    /**
     * Get summary of notifications for a specific user
     */
    public function getResume()
    {
        $userId = auth()->user()->id;

        $allServices = ['alignment', 'balancing', 'battery', 'oil', 'rotation', 'tire'];

        $notifications = $this->model::select('vehicle_id', 'topic_1', DB::raw('COUNT(*) as count_notification'))
            ->where('user_id', $userId)
            ->where('status', 'send')
            ->whereIn('topic_1', $allServices)
            ->groupBy('vehicle_id', 'topic_1')
            ->get();

        $resume = [];

        foreach ($notifications as $notification) {
            $vehicleId = $notification->vehicle_id;
            $service = $notification->topic_1;
            $countNotification = $notification->count_notification;

            if (!isset($resume[$vehicleId])) {
                $resume[$vehicleId] = [
                    'vehicle_id' => $vehicleId,
                    'total_notifications' => 0,
                    'services' => []
                ];
            }

            $resume[$vehicleId]['total_notifications'] += $countNotification;

            $resume[$vehicleId]['services'][] = [
                'service' => $service,
                'count_notification' => $countNotification
            ];
        }

        foreach ($resume as $vehicleId => &$data) {
            foreach ($allServices as $service) {
                if (!in_array($service, array_column($data['services'], 'service'))) {
                    $data['services'][] = [
                        'service' => $service,
                        'count_notification' => 0
                    ];
                }
            }
        }

        return array_values($resume);
    }
}
