<?php
namespace Jiny\Menu;

use \Jiny\Core\Registry;

class HTML
{
    public static function topTree($level=NULL)
    {
        // echo __METHOD__."<br>";
        $Menu = \Jiny\Core\Registry\Registry::get("Menu");

        if ($Menu->_tree->css){
            $str = "<ul class=\"".$Menu->_tree->css."\">";
        } else {
            $str = "<ul>";
        }
        
        foreach ($Menu->_tree->menu as $value) {
            $str .= self::li($value);
        }
        $str .= "</ul>";
        return $str;
    }

    public static function li($value)
    {
        if ($value->css) {
            $str .= "<li class='".$value->css."'>";
        } else {
            $str .= "<li>";
        }

        $str .= self::href($value);  
                   
        $str .= "</li>";
        return $str;
    }

    public static function href($value)
    {
        if ($value->href){
            if ($value->css_link) {
                $str .= "<a class='".$value->css_link."' href='".$value->href."'>";
            } else {
                $str .= "<a href='".$value->href."'>";
            }
            $str .= $value->name;
            $str .= "</a>";

        } else {
            $str .= $value->name;
        }

        return $str;
    }

    public static function subTree($href, $level=NULL)
    {
        echo __METHOD__."<br>";


    }

    /**
     * 
     */
}