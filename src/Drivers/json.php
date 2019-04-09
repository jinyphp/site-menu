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

class JSON extends \Jiny\Menu\Driver
{
    /**
     * 의존성 주입
     */
    public function __construct()
    {
        // 
    }
    
    public function load($path)
    {
        $json = json_decode(file_get_contents($path), TRUE);
        // print_r($json);
        return $json;
    }

    /**
     * 
     */
}