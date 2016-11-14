<?php

namespace JmWri\AmazonWishlist\Source;

use phpQuery;
use phpQueryObject;

/**
 * Class BaseSource
 * @package JmWri\AmazonWishlist\Source
 */
abstract class BaseSource
{

    protected $basePath = '/';

    protected $affiliateTag;


    /**
     * @param string $path
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function setBasePath($path)
    {
        if (! is_string($path)) {
            throw new \InvalidArgumentException('Base path is not a string');
        }
        if (! strlen($path)) {
            throw new \InvalidArgumentException('Base path is empty');
        }

        $this->basePath = $path;
        return true;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param $params
     *
     * @return string
     */
    abstract public function getPathWithParams($params);

    /**
     * @param $url
     * @param $params
     *
     * @return string
     */
    abstract protected function addParamsToUrl($url, $params);

    /**
     * According to https://affiliate-program.amazon.co.uk/gp/associates/help/t5/a21
     *
     * > So if you need to build a simple text link to a specific item on Amazon.co.uk, here is the link format you
     * need to use: http://www.amazon.co.uk/dp/ASIN/ref=nosim?tag=YOURASSOCIATEID
     *
     * e.g. http://www.amazon.co.uk/dp/B00U7EXH72/ref=nosim?tag=shkspr-21
     *
     * Is this the same for all countries?
     *
     * Your Associate ID only works with one country
     * https://affiliate-program.amazon.co.uk/gp/associates/help/t22/a13%3Fie%3DUTF8%26pf_rd_i%3Dassoc_he..
     *
     * @param string $aisn
     *
     * @param null|string $tag
     *
     * @return string
     */
    abstract public function getAffiliateLink($aisn, $tag);

    /**
     * Get the ASIN of the product
     *
     * ASIN is a 10 character string - https://en.wikipedia.org/wiki/Amazon_Standard_Identification_Number
     * Typical URls
     * http://www.amazon.co.uk/dp/B00IKIKDOM/ref=wl_it_dp_v_nS_ttl/278-9067265-3899254?_encoding=UTF8&colid=15SCUHW5RONL6&coliid=ICBVF90YVEFF4&psc=1
     * http://www.amazon.co.jp/dp/B00OOD4RLC/ref=wl_it_dp_v_S_ttl/376-5041442-9894656?_encoding=UTF8&colid=HIOT27YDKDXW&coliid=I2199742B598MC
     * http://www.amazon.de/dp/B00TRQ0CSI/ref=wl_it_dp_v_nS_ttl/275-4926214-1753269?_encoding=UTF8&colid=X82UNL4VMFM9&coliid=I2G0K6DW0S1MHT
     * http://www.amazon.ca/dp/B00A7WDYYU/ref=wl_it_dp_v_nS_ttl/180-5102401-8253319?_encoding=UTF8&colid=3OR5VN6044A6I&coliid=I30UZD33GMHBK3
     * http://www.amazon.com/dp/B00DQYNKCM/ref=wl_it_dp_v_nS_ttl/191-5492771-2500240?_encoding=UTF8&colid=37XI10RRD17X2&coliid=ILV2H2MHRX7HU&psc=1
     * http://www.amazon.es/dp/B007RXO716/ref=wl_it_dp_v_nS_ttl/279-0188662-6542856?_encoding=UTF8&colid=1ORXZQUAJ8H96&coliid=I35SODJIWT2DTA
     * http://www.amazon.in/dp/B00E81GGGY/ref=wl_it_dp_v_nS_ttl/275-0633160-8128200?_encoding=UTF8&colid=1HCKFSCVFG2UW&coliid=IUH4Z2TCPKDF2
     * http://www.amazon.it/dp/B00Y0O5L6U/ref=wl_it_dp_v_nS_ttl/280-4610661-1922667?_encoding=UTF8&colid=2RPUB231AJ78D&coliid=IFOHKF3REGMUU&psc=1
     * http://www.amazon.com.br/dp/B00YSILJZU/ref=wl_it_dp_v_nS_ttl/177-3586976-1287133?_encoding=UTF8&colid=3OF5TPV1ZMWLM&coliid=I36RLGSQVQOUYH
     * http://www.amazon.fr/dp/B003FRXFWK/ref=wl_it_dp_v_S_ttl/275-1202652-6343230?_encoding=UTF8&colid=1EHE58O7QKWTH&coliid=I3EGD6MPOTC9GY
     *
     * @param string $productLink
     *
     * @return string
     */
    abstract public function getAsin($productLink);

    /**
     * @param string $link
     *
     * @return string
     */
    abstract public function getFullLink($link);

    /**
     * @param string $url
     *
     * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function getDocumentFile($url)
    {
        return phpQuery::newDocumentFile($url);
    }

    /**
     * @param array $params
     *
     * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
     */
    public function getDocumentFileWithParams($params)
    {
        return phpQuery::newDocumentFile($this->getPathWithParams($params));
    }

    /**
     * @param array $params
     *
     * @param int $page
     *
     * @return \JmWri\AmazonWishlist\Source\QueryTemplatesParse|\JmWri\AmazonWishlist\Source\QueryTemplatesSource|\JmWri\AmazonWishlist\Source\QueryTemplatesSourceQuery|\phpQueryObject
     */
    public function getDocumentFilePagedWithParams($params, $page)
    {
        $params['page'] = $page;
        return phpQuery::newDocumentFile($this->getPathWithParams($params));
    }

}
