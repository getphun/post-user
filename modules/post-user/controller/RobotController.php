<?php
/**
 * User post robot provider
 * @package user-post
 * @version 0.0.1
 * @upgrade true
 */

namespace PostUser\Controller;
use Post\Model\Post;
use PostUser\Library\Robot;
use User\Model\User;

class RobotController extends \SiteController
{
    public function feedAction(){
        if(!module_exists('robot'))
            return $this->show404();
        
        $name = $this->param->name;
        
        $user = User::get(['name'=>$name], false);
        if(!$user)
            return $this->show404();
        
        $user = \Formatter::format('user', $user, false);
        
        $feed = (object)[
            'url'         => $this->router->to('sitePostUserFeed', ['name'=>$user->name]),
            'description' => '', // what content should we put here?
            'updated'     => null,
            'host'        => $user->page,
            'title'       => hs($user->fullname)
        ];
        
        $pages = Robot::feedPost($user);
        $this->robot->feed($feed, $pages);
    }
}