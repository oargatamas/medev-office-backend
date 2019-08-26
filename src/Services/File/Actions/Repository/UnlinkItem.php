<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:18
 */

namespace MedevOffice\Services\File\Actions\Repository;


use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class UnlinkItem extends APIRepositoryAction
{
    const ITEM_ID = "itemId";

    /**
     * @param $args
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];

        $this->database->delete("Archive_ItemPermissions",
            [
                "ItemId" => $itemId
            ]);

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Permissions can not be removed from item. ".implode(" - ",$result));
        }
    }
}