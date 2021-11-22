<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Menu;

class Data
{
    private $arr;
    public function __construct($file)
    {
        echo __CLASS__;
        $this->arr = \jiny\json_get_object($file);
    }

    public function get($key="title")
    {
        $arr=[];
        foreach($this->arr as $k => $v) 
        {
            $arr[$k] = $v->$key;
        }
        return $arr;
        //return $this->arr;
    }

}