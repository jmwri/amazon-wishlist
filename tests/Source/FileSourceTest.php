<?php

namespace JmWri\AmazonWishlist\Test\Source;

use JmWri\AmazonWishlist\Source\FileSource;
use JmWri\AmazonWishlist\Test\BaseTest;

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
     * @var string[]
     */
    protected static $extensionValid;

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

        self::$extensionValid = [
            '.html',
        ];

        self::$source = new FileSource(self::$basePathValid[0], self::$extensionValid[0]);
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
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setExtension('.xml');
    }

    public function testSetExtensionInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setExtension(123);
    }

    public function testSetExtensionInvalidEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        self::$source->setExtension('');
    }

    public function testGetDocumentFile()
    {
        $documentFile = self::$source->getDocumentFile(__DIR__ . '/../html/basic.html');
        $this->assertEquals('<p>my basic html</p>', $documentFile->html());
    }

    public function testGetPathWithNoParams()
    {
        self::$source->setBasePath('test_path');
        $url = self::$source->getPathWithParams([]);
        $this->assertEquals('test_path.html', $url);
    }

}
