<?php
namespace Jiny\Menu;

include "component.php";
include "composite.php";

$root = new \Jiny\Menu\Composite("root");
print_r($root);

$obj = json_encode($root);
echo $obj."\n";

$o = json_decode($obj);
print_r($o);

/*
function tree()
{
    echo "Composite Pattern <br>";
   
    // 폴더
    $root = new Composite("root");
    
    $home1 = new Composite("home1");
    $home2 = new Composite("home2");
  
        $hojin = new Composite("hojin");
        $jiny = new Composite("jiny");
    $users = new Composite("user");
    $temp = new Composite("temp");

    // 파일
    $img1 = new Leaf("img1");
    $img2 = new Leaf("img2");
    $img3 = new Leaf("img3");
    $img4 = new Leaf("img4");
   

    // 
    // 상단에 서브 컴포넌트(폴더)를 추가합니다.
    $root->addNode($home1);
    $root->addNode($home2);
  
    $root->addNode($users);
    // 서브폴더를 추가
    $users->addNode($hojin);
        // 파일(leaf)추가
        $hojin->addNode($img1);
        $hojin->addNode($img2);
        $hojin->addNode($img3);
        $hojin->addNode($img4);
    $users->addNode($jiny);
    $root->addNode($temp);
 

    echo "<pre>";    
    var_dump($root);
    var_dump($home);
    echo "</pre>";

    // 컴포짓 노트 트리를 출력합니다.
    show($root);
}

function show($component) {
    echo "<hr>";
    echo "트리를 출력합니다.<br>";
    $arr = $component->_children;
    foreach ($arr as $key => $value) {
        
        if ($value instanceof Composite) {
            echo "Composite = ". $key. "<br>";

        } else if ($value instanceof Leaf) {
            echo "Leaf = ". $key. "<br>";
            
        } else {
            echo "??<br>";
        }

        // 재귀호출 탐색
        if ($value) show($value);

    }
}
*/
