<?php

namespace JmWri\AmazonWishlist\Test\Source;

use JmWri\AmazonWishlist\Source\AmazonSource;
use JmWri\AmazonWishlist\Test\BaseTest;

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
     * @var string[]
     */
    protected static $idValid;

    /**
     * @var string[]
     */
    protected static $tldValid;

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

        self::$idValid = [
            '2EZ944B2S8C5Q',
            '7IS947A8S9H3E',
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

        self::$source = new AmazonSource(self::$idValid[0], self::$tldValid[0]);
    }

    public function testSetBasePathValid()
    {
        foreach (self::$basePathValid as $path) {
            $this->assertTrue(self::$source->setBasePath($path));
        }
    }

    public function testSetBasePathInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setBasePath(1234);
    }

    public function testSetBasePathInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setBasePath('');
    }

    public function testSetBasePathInvalidNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setBasePath(null);
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

    public function testSetIdInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setId(1234);
    }

    public function testSetIdInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setId('');
    }

    public function testSetIdInvalidNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setId(null);
    }

    public function testSetTldValid()
    {
        foreach (self::$tldValid as $tld) {
            $this->assertTrue(self::$source->setTld($tld));
        }
    }

    public function testSetTldInvalidString()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setTld('not valid');
    }

    public function testSetTldInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setTld(1234);
    }

    public function testSetTldInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setTld('');
    }

    public function testGetDocumentFile()
    {
        $documentFile = self::$source->getDocumentFile(__DIR__ . '/../html/basic.html');
        $this->assertEquals('<p>my basic html</p>', $documentFile->html());
    }

}
