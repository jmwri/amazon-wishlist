<?php

namespace JmWri\AmazonWishlist\Test;

use JmWri\AmazonWishlist\AmazonWishlist;
use JmWri\AmazonWishlist\Source\AmazonSource;

/**
 * Class AmazonSourceTest
 * @package JmWri\AmazonWishlist\Test
 */
class AmazonSourceTest extends BaseTest
{

    /**
     * @var string[]
     */
    protected static $basePathValid;

    /**
     * @var mixed[]
     */
    protected static $basePathInvalid;

    /**
     * @var string[]
     */
    protected static $idValid;

    /**
     * @var mixed[]
     */
    protected static $idInvalid;

    /**
     * @var string[]
     */
    protected static $tldValid;

    /**
     * @var mixed[]
     */
    protected static $tldInvalid;

    /**
     * @var AmazonSource
     */
    protected static $source;

    public static function setUpBeforeClass()
    {
        self::$basePathValid = [
            '/some/dir',
            'amazon.co.uk',
        ];

        self::$basePathInvalid = [
            1234,
            '',
            null,
        ];

        self::$idValid = [
            '2EZ944B2S8C5Q',
            '7IS947A8S9H3E',
        ];

        self::$idInvalid = [
            1234,
            '',
            null,
        ];

        self::$tldValid = [
            '.co.uk',
            '.com',
            '.ca',
            '.com.br',
            '.co.jp',
            '.de',
            '.fr',
            '.in',
            '.it',
            '.es',
        ];

        self::$tldInvalid = [
            '.com.au',
            '.com.mx',
            '.nl',
            123,
            '',
        ];

        self::$source = new AmazonSource(self::$idValid[0], self::$tldValid[0]);
    }

    public function testSetBasePathValid()
    {
        foreach (self::$basePathValid as $id) {
            $this->assertTrue(self::$source->setBasePath($id));
        }
    }

    public function testSetBasePathInvalid()
    {
        foreach (self::$basePathInvalid as $id) {
            $this->expectException(\InvalidArgumentException::class);
            self::$source->setBasePath($id);
        }
    }

    public function testGetBasePath()
    {
        self::$source->setBasePath('test_path');
        $this->assertEquals('test_path' . self::$tldValid[0], self::$source->getBasePath());
    }

    public function testSetIdValid()
    {
        foreach (self::$idValid as $id) {
            $this->assertTrue(self::$source->setId($id));
        }
    }

    public function testSetIdInvalid()
    {
        foreach (self::$idInvalid as $id) {
            $this->expectException(\InvalidArgumentException::class);
            self::$source->setId($id);
        }
    }

    public function testSetTldValid()
    {
        foreach (self::$tldValid as $tld) {
            $this->assertTrue(self::$source->setTld($tld));
        }
    }

    public function testSetTldInvalid()
    {
        foreach (self::$tldInvalid as $tld) {
            $this->expectException(\InvalidArgumentException::class);
            self::$source->setTld($tld);
        }
    }

    public function testGetDocumentFile()
    {
        $documentFile = self::$source->getDocumentFile(__DIR__ . '/html/basic.html');
        $this->assertEquals('<p>my basic html</p>', $documentFile->html());
    }

}
