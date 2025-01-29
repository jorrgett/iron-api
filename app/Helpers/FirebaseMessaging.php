<?php

namespace App\Helpers;

use App\Models\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseMessaging
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = (new Factory)
            ->withServiceAccount(base_path('config/firebase/firebase-config.json'))
            ->createMessaging();
    }

    /**
     * Send push notifications to users who have pending notifications.
     */
    public function pushNotifications()
    {
        $notifications = UserNotification::with(['notification', 'user', 'vehicle:id,plate'])
            ->where('status', 'for send')
            ->get()
            ->groupBy(['user_id', 'notification_id', 'vehicle_id']);
    
        foreach ($notifications as $userId => $notificationGroups) {
            foreach ($notificationGroups as $notificationId => $vehicleGroups) {
                foreach ($vehicleGroups as $group) {
                    if ($group->isEmpty()) {
                        continue;
                    }
    
                    $firstItem = $group->first();
    
                    $user = $firstItem->user;
                    $vehicle = $firstItem->vehicle;
                    $notification = $firstItem->notification;
    
                    $tokens = $group->pluck('user.fcm_token')->unique()->filter()->toArray();
    
                    if (empty($tokens)) {
                        Log::warning("Usuario {$userId} no tiene tokens válidos.");
                        continue;
                    }
    
                    $this->sendNotification($user, $vehicle, $notification, $tokens, $group);
                }
            }
        }
    
        $notifications->each(function ($userGroups) {
            collect($userGroups)->each(function ($vehicleGroups) {
                collect($vehicleGroups)->each(function ($group) {
                    if (!$group->isEmpty()) {
                        $firstItem = $group->first();
                        $this->updateNotificationStatus($firstItem->notification_id);
                    }
                });
            });
        });
    }
    
    /**
     * Sends the notification to a set of FCM tokens.
     *
     * @param  mixed $notification
     * @param  array $tokens
     * @param  \Illuminate\Support\Collection $group
     * @return void
     */
    private function sendNotification($user, $vehicle, $notification, array $tokens, $group)
    {
        if (empty($tokens)) {
            Log::warning('Intento de envío sin tokens.');
            return;
        }

        $apnsConfig = ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => "VEHICULO {$vehicle->plate} - {$notification->title}",
                        'body'  => "Hola {$user->full_name}. {$notification->body}",
                    ],
                    'badge' => 0,
                    'sound' => 'default',
                ],
            ],
        ]);

        $androidConfig = AndroidConfig::fromArray([
            'ttl' => '3600s',
            'priority' => 'high',
            'notification' => [
                'title' => "VEHICULO {$vehicle->plate} - {$notification->title}",
                'body'  => "Hola {$user->full_name}. {$notification->body}",
                'icon' => 'stock_ticker_update',
                'color' => '#f45342',
                'sound' => 'default',
            ],
        ]);

        $message = CloudMessage::new()
            ->withNotification([
                'title' => "VEHICULO {$vehicle->plate} - {$notification->title}",
                'body'  => "Hola {$user->full_name}. {$notification->body}",
            ])
            ->withAndroidConfig($androidConfig)
            ->withApnsConfig($apnsConfig);

        try {
            $this->messaging->sendMulticast($message, $tokens);
        } catch (MessagingException $e) {
            Log::error('Error enviando notificación: ' . $e->getMessage());

            foreach ($group as $userNotification) {
                $userNotification->update([
                    'detail' => 'error'
                ]);
            }
        }
    }

    /**
     * Updates the status of notifications in the database after sending them.
     *
     * @param  mixed $notificationId
     * @return void
     */
    private function updateNotificationStatus($notificationId)
    {
        UserNotification::where('notification_id', $notificationId)
            ->where('status', 'for send')
            ->update([
                'status' => 'send',
                'sent_date' => Carbon::now(),
            ]);
    }
}
