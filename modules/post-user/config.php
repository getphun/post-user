<?php
/**
 * post-user config file
 * @package post-user
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'post-user',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/post-user',
    '__files' => [
        'modules/post-user/config.php'                      => [ 'install', 'remove', 'update' ],
        'modules/post-user/library'                         => [ 'install', 'remove', 'update' ],
        'modules/post-user/meta'                            => [ 'install', 'remove', 'update' ],
        'modules/post-user/controller/RobotController.php'  => [ 'install', 'remove', 'update' ],
        
        'modules/post-user/controller/UserController.php'   => [ 'install', 'remove' ],
        'theme/site/post/user'                              => [ 'install', 'remove' ]
    ],
    '__dependencies' => [
        'post',
        'user',
        'formatter',
        'site-meta',
        'site',
        '/robot',
        '/slug-history'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'PostUser\\Library\\Robot'              => 'modules/post-user/library/Robot.php',
            'PostUser\\Meta\\User'                  => 'modules/post-user/meta/User.php',
            'PostUser\\Controller\\RobotController' => 'modules/post-user/controller/RobotController.php',
            'PostUser\\Controller\\UserController'  => 'modules/post-user/controller/UserController.php'
        ],
        'files' => []
    ],
    '_routes' => [
        'site' => [
            'sitePostUserFeed' => [
                'rule' => '/user/:name/post/feed.xml',
                'handler' => 'PostUser\\Controller\\Robot::feed'
            ],
            'sitePostUser' => [
                'rule' => '/post/:name/post',
                'handler' => 'PostUser\\Controller\\User::single'
            ]
        ]
    ]
];