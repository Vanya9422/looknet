<?php
/*
|--------------------------------------------------------------------------
| Prettus Repository Config
|--------------------------------------------------------------------------
|
*/

use App\Repositories\V1\{Admin\Commercial\BusinessRepository,
    Admin\Commercial\BusinessRepositoryEloquent,
    Admin\Commercial\ClientRepository,
    Admin\Commercial\ClientRepositoryEloquent,
    Admin\Commercial\CommercialNotificationRepository,
    Admin\Commercial\CommercialNotificationRepositoryEloquent,
    Admin\Commercial\CommercialUserRepository,
    Admin\Commercial\CommercialUserRepositoryEloquent,
    Admin\Commercial\GroupRepository,
    Admin\Commercial\GroupRepositoryEloquent,
    Admin\ComplaintRepository,
    Admin\ComplaintRepositoryEloquent,
    Admin\Pages\PageRepository,
    Admin\Pages\PageRepositoryEloquent,
    Admin\RefusalRepository,
    Admin\RefusalRepositoryEloquent,
    Admin\Support\TicketRepository,
    Admin\Support\TicketRepositoryEloquent,
    Admin\Category\CategoryRepository,
    Admin\Category\CategoryRepositoryEloquent,
    Admin\Category\AnswerRepository,
    Admin\Category\FilterRepository,
    Admin\Category\FilterRepositoryEloquent,
    Admin\Category\AnswerRepositoryEloquent,
    AdvertiseRepository,
    AdvertiseRepositoryEloquent,
    Chat\ConversationRepository,
    Chat\ConversationRepositoryEloquent,
    Chat\MessageRepository,
    Chat\MessageRepositoryEloquent,
    CityRepository,
    CityRepositoryEloquent,
    LanguageRepository,
    LanguageRepositoryEloquent,
    Media\MediaRepository,
    Media\MediaRepositoryEloquent,
    ReviewRepository,
    ReviewRepositoryEloquent,
    StateRepository,
    StateRepositoryEloquent,
    Users\NotificationRepository,
    Users\NotificationRepositoryEloquent,
    Users\SubscriptionRepository,
    Users\SubscriptionRepositoryEloquent,
    Users\UserRepository,
    Users\UserRepositoryEloquent
};

return [
    'repositories' => [
        'V1' => [
            UserRepository::class => UserRepositoryEloquent::class,
            StateRepository::class => StateRepositoryEloquent::class,
            CityRepository::class => CityRepositoryEloquent::class,
            LanguageRepository::class => LanguageRepositoryEloquent::class,
            CategoryRepository::class => CategoryRepositoryEloquent::class,
            FilterRepository::class => FilterRepositoryEloquent::class,
            AnswerRepository::class => AnswerRepositoryEloquent::class,
            AdvertiseRepository::class => AdvertiseRepositoryEloquent::class,
            PageRepository::class => PageRepositoryEloquent::class,
            NotificationRepository::class => NotificationRepositoryEloquent::class,
            CommercialUserRepository::class => CommercialUserRepositoryEloquent::class,
            ClientRepository::class => ClientRepositoryEloquent::class,
            BusinessRepository::class => BusinessRepositoryEloquent::class,
            CommercialNotificationRepository::class => CommercialNotificationRepositoryEloquent::class,
            SubscriptionRepository::class => SubscriptionRepositoryEloquent::class,
            TicketRepository::class => TicketRepositoryEloquent::class,
            MediaRepository::class => MediaRepositoryEloquent::class,
            MessageRepository::class => MessageRepositoryEloquent::class,
            ConversationRepository::class => ConversationRepositoryEloquent::class,
            GroupRepository::class => GroupRepositoryEloquent::class,
            RefusalRepository::class => RefusalRepositoryEloquent::class,
            ReviewRepository::class => ReviewRepositoryEloquent::class,
            ComplaintRepository::class => ComplaintRepositoryEloquent::class,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Repository Pagination Limit Default
    |--------------------------------------------------------------------------
    |
    */
    'pagination' => [
        'limit' => 20
    ],

    /*
    |--------------------------------------------------------------------------
    | Fractal Presenter Config
    |--------------------------------------------------------------------------
    |  Available serializers:
    |  ArraySerializer
    |  DataArraySerializer
    |  JsonApiSerializer
    */
    'fractal' => [
        'params' => [
            'include' => 'include'
        ],
//        'serializer' => League\Fractal\Serializer\DataArraySerializer::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Config
    |--------------------------------------------------------------------------
    |
    */
    'cache' => [
        /*
         |--------------------------------------------------------------------------
         | Cache Status
         |--------------------------------------------------------------------------
         |
         | Enable or disable cache
         |
         */
        'enabled' => env('ENABLE_CACHE_REPOSITORY', false),

        /*
         |--------------------------------------------------------------------------
         | Cache Minutes
         |--------------------------------------------------------------------------
         |
         | Time of expiration cache
         |
         */
        'minutes' => 30,

        /*
         |--------------------------------------------------------------------------
         | Cache Repository
         |--------------------------------------------------------------------------
         |
         | Instance of Illuminate\Contracts\Cache\Repository
         |
         */
        'repository' => 'cache',

        /*
          |--------------------------------------------------------------------------
          | Cache Clean Listener
          |--------------------------------------------------------------------------
          |
          |
          |
          */
        'clean' => [

            /*
              |--------------------------------------------------------------------------
              | Enable clear cache on repository changes
              |--------------------------------------------------------------------------
              |
              */
            'enabled' => true,

            /*
              |--------------------------------------------------------------------------
              | Actions in Repository
              |--------------------------------------------------------------------------
              |
              | create : Clear Cache on create Entry in repository
              | update : Clear Cache on update Entry in repository
              | delete : Clear Cache on delete Entry in repository
              |
              */
            'on' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ]
        ],

        'params' => [
            /*
            |--------------------------------------------------------------------------
            | Skip Cache Params
            |--------------------------------------------------------------------------
            |
            |
            | Ex: http://prettus.local/?search=lorem&skipCache=true
            |
            */
            'skipCache' => 'skipCache'
        ],

        /*
       |--------------------------------------------------------------------------
       | Methods Allowed
       |--------------------------------------------------------------------------
       |
       | methods cacheable : all, paginate, find, findByField, findWhere, getByCriteria
       |
       | Ex:
       |
       | 'only'  =>['all','paginate'],
       |
       | or
       |
       | 'except'  =>['find'],
       */
        'allowed' => [
            'only' => null,
            'except' => null
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Criteria Config
    |--------------------------------------------------------------------------
    |
    | Settings of request parameters names that will be used by Criteria
    |
    */
    'criteria' => [
        /*
        |--------------------------------------------------------------------------
        | Accepted Conditions
        |--------------------------------------------------------------------------
        |
        | Conditions accepted in consultations where the Criteria
        |
        | Ex:
        |
        | 'acceptedConditions'=>['=','like']
        |
        | $query->where('foo','=','bar')
        | $query->where('foo','like','bar')
        |
        */
        'acceptedConditions' => [
            '=',
            '>',
            '<',
            '>=',
            '<=',
            '<>',
            '!=',
            'like',
            'null',
            'in',
            'not in',
            'between'
        ],
        /*
        |--------------------------------------------------------------------------
        | Request Params
        |--------------------------------------------------------------------------
        |
        | Request parameters that will be used to filter the query in the repository
        |
        | Params :
        |
        | - search : Searched value
        |   Ex: http://prettus.local/?search=lorem
        |
        | - searchFields : Fields in which research should be carried out
        |   Ex:
        |    http://prettus.local/?search=lorem&searchFields=name;email
        |    http://prettus.local/?search=lorem&searchFields=name:like;email
        |    http://prettus.local/?search=lorem&searchFields=name:like
        |
        | - filter : Fields that must be returned to the response object
        |   Ex:
        |   http://prettus.local/?search=lorem&filter=id,name
        |
        | - orderBy : Order By
        |   Ex:
        |   http://prettus.local/?search=lorem&orderBy=id
        |
        | - sortedBy : Sort
        |   Ex:
        |   http://prettus.local/?search=lorem&orderBy=id&sortedBy=asc
        |   http://prettus.local/?search=lorem&orderBy=id&sortedBy=desc
        |
        | - searchJoin: Specifies the search method (AND / OR), by default the
        |               application searches each parameter with OR
        |   EX:
        |   http://prettus.local/?search=lorem&searchJoin=and
        |   http://prettus.local/?search=lorem&searchJoin=or
        |
        */
        'params' => [
            'search' => 'search',
            'searchFields' => 'searchFields',
            'filter' => 'filter',
            'orderBy' => 'orderBy',
            'sortedBy' => 'sortedBy',
            'with' => 'with',
            'searchJoin' => 'searchJoin',
            'withCount' => 'withCount'
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Generator Config
    |--------------------------------------------------------------------------
    |
    */
    'generator' => [
        'basePath' => app()->path(),
        'rootNamespace' => 'App\\',
        'stubsOverridePath' => app()->path(),
        'paths' => [
            'models' => 'Entities',
            'repositories' => 'Repositories',
            'interfaces' => 'Repositories',
            'transformers' => 'Transformers',
            'presenters' => 'Presenters',
            'validators' => 'Validators',
            'controllers' => 'Http/Controllers',
            'provider' => 'RepositoryServiceProvider',
            'criteria' => 'Criteria'
        ]
    ]
];
