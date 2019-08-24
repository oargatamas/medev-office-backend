<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 12:39
 */

namespace MedevOffice\Services\File\Actions\Repository\Permission;


use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class ClearItemPermission extends APIRepositoryAction
{
    const ITEM_ID = "itemId";

    /**
     * @param $args
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];


        $this->database->delete(Permission::getTableName(),
            ["ItemId" => $itemId,]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Permissions can not be removed from item. ".implode(" - ",$result));
        }
    }
}