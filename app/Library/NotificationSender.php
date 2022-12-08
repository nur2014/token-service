<?php
namespace App\Library;

class NotificationSender
{
    /**
     * Send notification to user
     *
     * @param int $buttonId
     * @param int $receiverId
     * @param int $senderId
     * @param string $messageType
     * @param string $message
     * @param array $media
     * @param array $recipientTypes
     * @param null|string $url
     * @param null|string $componentId
     * @return mixed
     */
    public static function sendNotification($buttonId, $receiverId, $senderId = 0, $messageType = '', $message = '', $media = [], $recipientTypes = [], $menuUrl = null, $componentId = null)
    {
        $baseUrl = config('app.base_url.common_service');
        $uri = '/notification-sender/send-notification';
        //$menuUrl = app('request')->header('accessMenuName');
//        $menuUrl = 'http://localhost:8080/agri-marketing/crop-price-info/price-information/market-commodity-price-list';
        $data = [
            'menu_url' => $menuUrl,
            'button_id' => $buttonId,
            'receiver_id' => $receiverId,
            'sender_id' => $senderId,
            'message_type' => $messageType,
            'message' => $message,
            'media' => $media,
            'recipient_types' => $recipientTypes,
            'component_id' => $componentId
        ];

        $response = \App\Library\RestService::postData($baseUrl, $uri, $data);

        return json_decode($response, true);
    }
}