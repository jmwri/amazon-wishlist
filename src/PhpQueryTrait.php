<?php

namespace JmWri\AmazonWishlist;

use phpQuery;
use phpQueryObject;

/**
 * Class PhpQueryTrait
 * @package JmWri\AmazonWishlist
 */
trait PhpQueryTrait
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
