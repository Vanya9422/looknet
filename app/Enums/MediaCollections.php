<?php declare(strict_types=1);

namespace App\Enums;

use App\Models\Admin\Categories\Category;
use App\Models\Admin\Commercial\Client;
use App\Models\Admin\Commercial\CommercialBusiness;
use App\Models\Admin\Commercial\CommercialNotification;
use App\Models\Admin\Commercial\CommercialUsers;
use App\Models\Admin\Support\Page;
use App\Models\Admin\Support\Ticket;
use App\Models\Products\Advertise;
use App\Models\Chat\Message;
use App\Models\User;
use BenSampo\Enum\Enum;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class MediaCollections
 * @package App\Enums
 */
final class MediaCollections extends Enum
{
    /**
     * Коллекция Используется для Аватара профиля пользователя
     */
    public const USER_AVATAR_COLLECTION = 'avatar';

    /**
     * Коллекция Используется для Подделного Аватара Феикового профиля пользователя
     */
    public const USER_FAKE_AVATAR_ADVERTISE = 'fake_avatar_advertise';

    /**
     * Коллекция Используется для Аватара профиля пользователя
     */
    public const MODERATOR_USER_AVATAR_COLLECTION = 'user_avatar_from_moderator';

    /**
     * Коллекция Используется для Картинки Категории
     */
    public const PICTURE_COLLECTION = 'admin_picture';

    /**
     * Коллекция Используется для коммерческого пользователов в Админке
     */
    public const COMMERCIAL_USER_AVATAR = 'commercial_user_avatar';

    /**
     * Коллекция Используется для Тикетов службу поддержки
     */
    public const SUPPORT_TICKET_FILE = 'support_ticket_file';

    /**
     * Коллекция Используется для Тикетов службу поддержки
     */
    public const CHAT_FILES = 'chat_files';

    /**
     * Коллекция Используется для жалоби в чатах
     */
    public const CONVERSATION_COMPLAINT_FILE = 'conversation_complaint_file';

    /**
     * Коллекция Используется для жалоби на отзивах публикации
     */
    public const REVIEWS_COMPLAINT_FILE = 'review_complaint_file';

    /**
     * Коллекция Используется для фотки орзивов
     */
    public const REVIEW_PICTURES = 'pictures_review';

    /**
     * Коллекция Используется для колекциа клиента Комерции
     */
    public const COMMERCIAL_CLIENT_AVATAR = 'commercial_client_avatar';

    /**
     * Коллекция Используется для коммерческого бизнеса в Админке
     */
    public const COMMERCIAL_BUSINESS_BANNER = 'commercial_business_banner';

    /**
     * Коллекция Используется для коммерческого уведомлении в Админке
     */
    public const COMMERCIAL_NOTIFICATION_BANNER = 'commercial_notification_banner';

    /**
     * Коллекция Используется для аватарки обновления
     */
    public const ADVERTISE_COLLECTION = 'product_gallery';

    /**
     * Коллекция Используется для картинки страници в Админке (текстовие странички)
     */
    public const BACKGROUND_COLLECTION = 'background';

    /**
     * Коллекция Используется для администраторов и модераторов
     */
    public const ADMIN_FILES = 'admin_files';

    /**
     * @param HasMedia $model
     * @return string
     */
    public static function getCollectionNameByModelType(HasMedia $model): string {
        return self::getCollectionNames()[get_class($model)];
    }

    /**
     * @return array
     */
    public static function getCollectionNames(): array {
        return [
            User::class => self::USER_AVATAR_COLLECTION,
            Category::class => self::PICTURE_COLLECTION,
            CommercialUsers::class => self::COMMERCIAL_USER_AVATAR,
            Ticket::class => self::SUPPORT_TICKET_FILE,
            Message::class => self::CHAT_FILES,
            Client::class => self::COMMERCIAL_CLIENT_AVATAR,
            CommercialBusiness::class => self::COMMERCIAL_BUSINESS_BANNER,
            CommercialNotification::class => self::COMMERCIAL_NOTIFICATION_BANNER,
            Advertise::class => self::ADVERTISE_COLLECTION,
            Page::class => self::BACKGROUND_COLLECTION,
        ];
    }
}
