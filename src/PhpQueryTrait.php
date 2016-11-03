<?php

namespace JmWri\AmazonWishlist;

use phpQuery;
use phpQueryObject;

/**
 * Class UtilTrait
 * @package JmWri\AmazonWishlist
 */
trait UtilTrait
{

    /**
     * @param $url
     *
     * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    protected function getDocumentFile($url)
    {
        return phpQuery::newDocumentFile($url);
    }

}
