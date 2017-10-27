<?php
/**
 * Post user robot provider
 * @package post-user
 * @version 0.0.1
 * @upgrade true
 */

namespace PostUser\Library;
use Post\Model\Post;

class Robot{

    static function feedPost($user){
        $result = [];
        
        $last2days = date('Y-m-d H:i:s', strtotime('-2 days'));
        
        $posts = Post::get([
            'user'     => $user->id,
            'status'   => 4,
            'updated'  => ['__op', '>=', $last2days]
        ]);
        
        if(!$posts)
            return $result;
        
        $posts = \Formatter::formatMany('post', $posts, false, ['content', 'user', 'category']);
        
        foreach($posts as $post){
            $desc = $post->meta_description->safe;
            if(!$desc)
                $desc = $post->content->chars(160);
            
            $row = (object)[
                'author'      => hs($post->user->fullname),
                'description' => $desc,
                'page'        => $post->page,
                'published'   => $post->created->format('r'),
                'updated'     => $post->updated->format('c'),
                'title'       => $post->title->safe
            ];
            
            if($post->category){
                $row->categories = [];
                foreach($post->category as $cat)
                    $row->categories[] = $cat->name->safe;
            }
            
            $result[] = $row;
        }
        
        return $result;
    }
}