<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 30.
 * Time: 15:42
 */

namespace MedevOffice\Services\File\Middleware;


/**
 * Interface PermissionRestricted
 * @package MedevOffice\Services\File\Middleware
 */
interface PermissionRestricted
{

    /**
     * @return string[]
     */
    public static function getPermissionCodes();
}