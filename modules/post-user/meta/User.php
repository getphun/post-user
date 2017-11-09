<?php
/**
 * User post meta provider
 * @package post-user
 * @version 0.0.1
 * @upgrade true
 */

namespace PostUser\Meta;

class User
{
    static function single($user){
        $dis = \Phun::$dispatcher;
        
        $base_url = $dis->router->to('siteHome');
        
        $meta_desc  = 'All post and or news provided by '.$user->fullname;
        $meta_image = $base_url . 'theme/site/static/logo/500x500.png';
        $meta_url   = $user->page;
        $meta_title = $user->fullname;
        $meta_keys  = '';
        
        $page = $dis->req->getQuery('page', 1);
        if($page && $page > 1){
            $meta_title = sprintf('Page %s %s', $page, $meta_title);
            $meta_desc  = sprintf('Page %s %s', $page, $meta_desc);
            $meta_url   = $meta_url . '?page=' . $page;
        }
        
        // metas
        $single = (object)[
            '_schemas' => [],
            '_metas'   => [
                'title'         => $meta_title,
                'canonical'     => $meta_url,
                'description'   => $meta_desc,
                'keywords'      => $meta_keys,
                'image'         => $meta_image,
                'type'          => 'website'
            ]
        ];
        
        // my rss feed?
        if(module_exists('robot'))
            $single->_metas['feed'] = $dis->router->to('sitePostUserFeed', ['name'=>$user->name]);
        
        // user schema
        $schema = [
            '@context'      => 'http://schema.org',
            '@type'         => 'CollectionPage',
            'name'          => $user->fullname,
            'description'   => $meta_desc,
            'publisher'     => $dis->meta->schemaOrganization(),
            'url'           => $meta_url,
            'image'         => $meta_image
        ];
        $single->_schemas[] = $schema;
        
        // schema breadcrumbs
        $second_item = [
            '@type' => 'ListItem',
            'position' => 2,
            'item' => [
                '@id' => $base_url . '#user',
                'name' => 'User'
            ]
        ];
        
        $schema = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $base_url,
                        'name' => $dis->config->name
                    ]
                ],
                $second_item
            ]
        ];
        
        $single->_schemas[] = $schema;
        
        return $single;
    }
}