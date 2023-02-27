<?php


namespace Unit;

use Classes\NextSmallerNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NextSmallerNumberTest extends TestCase
{
    protected const DATASET = [3, 3, 4, 4, 6, 9, 10, 12, 12, 12, 14, 15, 15, 17, 19, 21, 21];

    /**
     * Test __construct method
     * @return void
     */
    public function test__construct(): void
    {
        // Check if we can create instance with valid dataset
        $this->assertInstanceOf(NextSmallerNumber::class, new NextSmallerNumber(self::DATASET));

        // Check if we can't create instance with empty dataset
        $this->expectException(InvalidArgumentException::class);
        new NextSmallerNumber([]);

        // Check if we can't create instance with invalid dataset
        $this->expectException(InvalidArgumentException::class);
        new NextSmallerNumber([1, 2, '3']);
    }

    /**
     * Test simpleGet method
     * @return void
     */
    public function testSimpleGet(): void
    {
        $this->check(__FUNCTION__);
    }

    /**
     * Test binarySearchGet method
     * @return void
     */
    public function testBinarySearchGet(): void
    {
        $this->check(__FUNCTION__);

    }

    /**
     * Test recursiveBinarySearchGet method
     * @return void
     */
    public function testRecursiveBinarySearchGet(): void
    {
        $this->check(__FUNCTION__);

    }

    /**
     * Check method
     * @param string $testMethodName
     * @return void
     */
    protected function check(string $testMethodName): void
    {
        // Get method name for test
        $methodName = preg_replace_callback('/test(?<firstLetter>\w)(?<tail>.*)/',
            static function ($matches) {
                return strtolower($matches['firstLetter']) . $matches['tail'];
            }, $testMethodName);

        // Initialize dataset
        $dataset = self::DATASET;
        shuffle($dataset);

        // Get instance
        $instance = new NextSmallerNumber($dataset);

        $min = min($dataset);
        $max = max($dataset);

        // Let's check out of range values
        $this->assertEquals(-1, $instance->$methodName($min - 1));
        $this->assertEquals($max, $instance->$methodName($max + 1));

        // Let's check min value
        $this->assertEquals(-1, $instance->$methodName($min));

        // Prepare sorted dataset for test
        sort($dataset);

        // Let's check tail
        for ($i = 1, $iMax = count(self::DATASET); $i < $iMax; $i++) {
            $valid = self::DATASET[$i - 1] === self::DATASET[$i] ? $valid ?? -1 : self::DATASET[$i - 1];
            $this->assertEquals($valid, $instance->$methodName(self::DATASET[$i]));
        }

        // Let's check invalid value
        $this->assertEquals(-1, $instance->$methodName(null));
    }
}
