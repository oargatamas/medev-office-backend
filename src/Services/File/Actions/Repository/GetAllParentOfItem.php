<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 15.
 * Time: 15:17
 */

namespace MedevOffice\Services\File\Actions\Repository;


use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetAllParentOfItem extends APIRepositoryAction
{
    const ITEM_ID = "itemId";
    const USER_ID = "userId";

    /**
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];
        $userId = $args[self::USER_ID];

        $getParent = new GetItemParent($this->service);

        $result = [];

        while ($parent = $getParent->handleRequest([
            GetItemParent::ITEM_ID => $itemId,
            GetItemParent::USER_ID => $userId,
        ])) {
            $result[] = $parent;
            $itemId = $parent->getIdentifier();
        }

        return $result;
    }
}