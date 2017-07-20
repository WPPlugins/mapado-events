<?php

namespace Mapado\Sdk\Transformer;

use DateTime;
use Mapado\Sdk\Model\Activity;

/**
 * ActivityTransformer
 *
 * @uses AbstractTransformer
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class ActivityTransformer extends AbstractTransformer
{
    /**
     * addressTransformer
     *
     * @var AddressTransformer
     * @access private
     */
    private $addressTransformer;

    /**
     * rubricTransformer
     *
     * @var RubricTransformer
     * @access private
     */
    private $rubricTransformer;

    /**
     * __construct
     *
     * @param AddressTransformer $addressTransformer
     * @access public
     */
    public function __construct(AddressTransformer $addressTransformer, RubricTransformer $rubricTransformer)
    {
        $this->addressTransformer = $addressTransformer;
        $this->rubricTransformer = $rubricTransformer;
    }

    /**
     * {@inheritedDoc}
     */
    public function transformItem(array $item)
    {
        $activity = new Activity();
        $activity
            ->setUuid($this->getFromArray($item, 'uuid'))
            ->setTitle($this->getFromArray($item, 'title'))
            ->setDescription($this->getFromArray($item, 'description'))
            ->setShortDescription($this->getFromArray($item, 'short_description'))
            ->setFrontPlaceName($this->getFromArray($item, 'front_place_name'))
            ->setSimplePrice($this->getFromArray($item, 'simple_price'))
            ->setUrlList($this->getFromArray($item, 'url_list'))
            ->setEmailList($this->getFromArray($item, 'email_list'))
            ->setPhoneList($this->getFromArray($item, 'phone_list'))
            ->setFormattedSchedule($this->getFromArray($item, 'formatted_schedule'))
            ->setCanceled($this->getFromArray($item, 'canceled'))
            ->setSoldOut($this->getFromArray($item, 'sold_out'))
            ->setShortDate($this->getFromArray($item, 'short_date'))
            ->setImageList($this->getFromArray($item, 'image_list'))
            ->setPriceList($this->getFromArray($item, 'price_list'));

        if (isset($item['_embedded']['address'])) {
            $activity->setAddress($this->addressTransformer->transformItem($item['_embedded']['address']));
        }

        if (isset($item['_embedded']['rubric'])) {
            $activity->setRubric($this->rubricTransformer->transformItem($item['_embedded']['rubric']));
        }

        if (!empty($item['first_date'])) {
            $activity->setFirstDate(new DateTime($item['first_date']));
        }
        if (!empty($item['last_date'])) {
            $activity->setLastDate(new DateTime($item['last_date']));
        }

        if (!empty($item['_embedded']['images'])) {
            $activity->setImageUrlList($item['_embedded']['images']);
        }

        if (!empty($item['_links'])) {
            $activity->setLinks($item['_links']);
        }

        return $activity;
    }
}
