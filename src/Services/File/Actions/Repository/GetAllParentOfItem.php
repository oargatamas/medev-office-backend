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

    /**
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];

        $getParent = new GetItemParent($this->service);

        $result = [];

        while ($parent = $getParent->handleRequest([
            GetItemParent::ITEM_ID => $itemId,
        ])) {
            $result[] = $parent;
            $itemId = $parent->getIdentifier();
        }

        return $result;
    }
}