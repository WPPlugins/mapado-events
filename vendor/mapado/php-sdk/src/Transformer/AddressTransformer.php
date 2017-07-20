<?php

namespace Mapado\Sdk\Transformer;

use Mapado\Sdk\Model\Address;

class AddressTransformer extends AbstractTransformer
{
    public function transformItem(array $item)
    {
        $return = new Address();
        $return
            ->setUuid($this->getFromArray($item, 'uuid'))
            ->setFormattedAddress($this->getFromArray($item, 'formatted_address'))
            ->setLatitude($this->getFromArray($item, 'latitude'))
            ->setLongitude($this->getFromArray($item, 'longitude'))
            ->setCity($this->getFromArray($item, 'city'))
        ;

        return  $return;
    }
}
