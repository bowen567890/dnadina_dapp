<?php


namespace App\Traits;


use App\Enums\QueueType;
use App\Http\Resources\PushNotificationResource;
use App\Jobs\SocketIoToUser;
use App\Models\Notification;
use App\Models\Notifications\BaseNotification;
use App\Models\User;
use Carbon\Carbon;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use GuzzleHttp\Client;

trait UserNotifiable
{

    /**
     * @param BaseNotification $instance
     */
    public function notify($instance)
    {
        /** @var User $user */
        $user = $this;


        $data = [
            'user_id' => $user->id,
            'type' => $instance->type,
            'socket' => $instance->socket,
            'forced' => $instance->forced,
            'title_slug' => $instance->title_slug,
            'content_slug' => $instance->content_slug,
            'params' => $instance->toParams(),
            'data' => $instance->toArray(),
            'read_time' => now(),
            'is_read' => false,
        ];
        $notification = Notification::query()->create($data);

        if ($notification->socket) {
            try {
                if (Carbon::make($user->last_active_at)->gt(now()->addMinutes(-60))) {
                    $notification->local = $user->local;
                    $httpClient = new Client();
                    $socket_url = Setting('socket_url');
                    $response = $httpClient->request(
                        'POST',
                        $socket_url.'/api/pusehr/to-user',
                        [
                            'json' => [
                                'tokens'=>[$user->id],
                                'data'  =>PushNotificationResource::make($notification),
                            ],
                            'timeout' => 10,
                        ]
                    );
                    //$responseStr = $response->getBody()->getContents();
                }

            } catch (\Exception $exception) {
                \Log::warning("notify 失败：" . $exception->getMessage());
            }
        }



    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Jenssegers\Mongodb\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Jenssegers\Mongodb\Relations\HasMany
     */
    public function readNotifications()
    {
        return $this->notifications()->read();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Jenssegers\Mongodb\Relations\HasMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

}
