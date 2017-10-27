<?php
/**
 * User post controller
 * @package post-user
 * @version 0.0.1
 * @upgrade false
 */

namespace PostUser\Controller;
use User\Model\User;
use Post\Model\Post;
use PostUser\Meta\User as _User;

class UserController extends \SiteController
{
    public function singleAction(){
        $name = $this->param->name;
        
        $user = User::get(['name'=>$name], false);
        if(!$user){
            if(module_exists('slug-history'))
                $this->slug->goto('user', $name, 'sitePostUser', ['name'=>$name]);
            return $this->show404();
        }
        
        $page = $this->req->getQuery('page', 1);
        $rpp = 12;
        
        $cache = 60*60*24*7;
        if($page > 1 || is_dev())
            $cache = null;
        
        $user = \Formatter::format('user', $user, true);
        $user->meta = _User::single($user);
        
        $params = [
            'user' => $user,
            'posts' => [],
            'pagination' => [],
            'total' => Post::count(['user'=>$user->id, 'status'=>4])
        ];
        
        // pagination
        if($params['total'] > $rpp)
            $params['pagination'] = calculate_pagination($page, $rpp, $params['total']);
        
        $posts = Post::get(['user'=>$user->id, 'status'=>4], $rpp, $page, 'published DESC');
        if($posts)
            $params['posts'] = \Formatter::formatMany('post', $posts, false, false);
        
        $this->respond('post/user/single', $params, $cache);
    }
}