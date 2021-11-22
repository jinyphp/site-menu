<?php
namespace Jiny\Menu;

//use \Jiny\Core\Registry;

class HTML
{
    private $Menu;
    public function __construct($menu)
    {
        $this->Menu = $menu;
    }
    
    public function ul($arr=[], $level=1)
    {
        $str = "<ul class='menu".$level."'>";
        foreach ( $this->Menu->arr as $key => $item) {
            $str .= $this->li($item, $level);
        }
        $str .= "</ul>";
        return $str;
    }

    private function li($obj, $level)
    {
        if(!$this->isEnable($obj)) return "";
        
        $str = "<li class='menu".$level."'>";
        $str .= "<a href='".$obj['href']."'>".$obj['title']."</a>";
        $ul = [];
        foreach ($obj as $key => $item) {
            if (\is_object($item) || $this->is_assoArray($item)) {
                $ul []= $this->li($item, $level);
            }
        }

        if(!empty($ul)) {
            
            $str .= "<ul class='menu".$level."'>";
            foreach($ul as $item) $str .= $item;
            $str .= "</ul>";
        }
        
        $str .= "</li>";
        return $str;
    }

    private function isEnable($obj)
    {
        if($obj['enable']) return true;
        return false;
    }

    private function is_assoArray($arr) : bool
    {
        if (\is_array($arr) && \array_keys($arr) !== range(0, count($arr) - 1)) {
            return true;
        } else {
            return false;
        }
    }

    



    /*
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
    */


    /**
     * 
     */
}