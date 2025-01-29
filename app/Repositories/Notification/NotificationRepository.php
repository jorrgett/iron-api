<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Repositories\BaseInterface;

class NotificationRepository implements BaseInterface
{
    protected $model;

    /**
     * Notification Repository Constructor.
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }

    /**
     * Get all paginated records
     * 
     * @param $data
     * @return NotificationCollection
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        return $this->model::where('is_active', true)->paginate($page);
    }

    /**
     * Store a newly created record in storage
     * 
     * @param array $data
     * @return Notification
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Display the specified record by field.
     * 
     * @param string $field
     * @param mixed $value
     * @param string $operator
     * @return Notification|null
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
    public function destroy($id)
    {
        $notification = $this->getByField('id', $id);

        $notification->is_active = false;
        $notification->deleted_at = now();
        
        $notification->save();
        return $notification;
    }

    /**
     * Update the specified record in storage
     * 
     * @param int $id
     * @param array $data
     * @return Notification|null
     */
    public function UpdateById($id, array $data)
    {
        $notification = $this->getByField('id', $id);

        if (!$notification) {
            return null;
        }

        $notification->fill($data);
        $notification->save();

        return $notification;
    }
}