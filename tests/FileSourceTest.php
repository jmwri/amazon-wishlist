<?php

namespace JmWri\AmazonWishlist\Test;

use JmWri\AmazonWishlist\Source\FileSource;

/**
 * Class FileSourceTest
 * @package JmWri\AmazonWishlist\Test
 */
class FileSourceTest extends BaseTest
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
    protected static $extensionValid;

    /**
     * @var mixed[]
     */
    protected static $extensionInvalid;

    /**
     * @var FileSource
     */
    protected static $source;

    public static function setUpBeforeClass()
    {
        self::$basePathValid = [
            '/some/dir',
            __DIR__ . '/html/basic'
        ];

        self::$basePathInvalid = [
            1234,
            '',
            null,
        ];

        self::$extensionValid = [
            '.html',
        ];

        self::$extensionInvalid = [
            123,
            '',
        ];

        self::$source = new FileSource(self::$basePathValid[0], self::$extensionValid[0]);
    }

    public function testSetBasePathValid()
    {
        foreach (self::$basePathValid as $path) {
            $this->assertTrue(self::$source->setBasePath($path));
        }
    }

    public function testSetBasePathInvalid()
    {
        foreach (self::$basePathInvalid as $path) {
            $this->expectException(\InvalidArgumentException::class);
            self::$source->setBasePath($path);
        }
    }

    public function testGetBasePath()
    {
        self::$source->setBasePath('test_path');
        $this->assertEquals('test_path', self::$source->getBasePath());
    }

    public function testSetExtensionValid()
    {
        foreach (self::$extensionValid as $extension) {
            $this->assertTrue(self::$source->setExtension($extension));
        }
    }

    public function testSetExtensionInvalid()
    {
        foreach (self::$extensionInvalid as $extension) {
            $this->expectException(\InvalidArgumentException::class);
            self::$source->setExtension($extension);
        }
    }

    public function testGetDocumentFile()
    {
        $documentFile = self::$source->getDocumentFile(__DIR__ . '/html/basic.html');
        $this->assertEquals('<p>my basic html</p>', $documentFile->html());
    }

}
