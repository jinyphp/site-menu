<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Menu\Drivers;

class PHP extends \Jiny\Menu\Driver
{
    /**
     * 의존성 주입
     */
    public function __construct()
    {
        // 
    }
    
    /**
     * PHP Return 배열로 된 설정값을 읽어 옵니다.
     * [
     *  "name"=>"aaa"
     * ]
     */
    public function load($path)
    {
        if (file_exists($path)) {
            return include ($path);
        } else {
            // 파일이 존재하지 않습니다.
        } 
    }

    /**
     * 
     */
}