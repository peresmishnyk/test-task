<?php

namespace Classes;

use InvalidArgumentException;

/**
 *  Get the next smaller number from dataset
 */
class NextSmallerNumber
{
    protected array $dataset;

    public function __construct(array $dataset)
    {
        // Check if dataset is not empty
        if (empty($dataset)) {
            throw new InvalidArgumentException("Invalid dataset. Must be an array with at least one element");
        }

        // Check if dataset is an array of integers
        array_walk($dataset, static function ($item) {
            if (!is_int($item)) {
                throw new InvalidArgumentException("Invalid dataset. Must be an array of integers");
            }
        });

        // Copy dataset, because we will sort it and don't want to change original dataset
        $this->dataset = $dataset;

        // Sort dataset
        sort($this->dataset);
    }

    /**
     * Simplest solution but not the fastest on big datasets
     *
     * @param mixed $number
     * @return int
     */
    public function simpleGet(mixed $number): int
    {
        // Check if number is integer and in range
        if (!is_null($testTypeAndRangeResult = $this->testTypeAndRange($number))) {
            return $testTypeAndRangeResult;
        }

        // Get max number from dataset that is less than given number or -1 if no such number
        return max(array_filter($this->dataset, static function ($item) use ($number) {
            return $item < $number;
        }) ?: [-1]);
    }

    /**
     * Recursive binary search solution, faster than simpleGet but slower and memory greedy than binarySearchGet
     *
     * @param mixed $number
     * @return int
     */
    public function recursiveBinarySearchGet(mixed $number): int
    {
        // Check if number is integer and in range
        if (!is_null($testTypeAndRangeResult = $this->testTypeAndRange($number))) {
            return $testTypeAndRangeResult;
        }

        // Recursive binary search
        return $this->recursiveBinarySearch($number, $this->dataset);
    }

    /**
     * Binary search solution, most efficient solution by time and memory
     *
     * @param mixed $number
     * @return int
     */
    public function binarySearchGet(mixed $number): int
    {
        // Check if number is integer and in range
        if (!is_null($testTypeAndRangeResult = $this->testTypeAndRange($number))) {
            return $testTypeAndRangeResult;
        }

        // Init boundaries
        $from = 0;
        $to = count($this->dataset) - 1;

        do {
            // Get middle position
            $position = $from + floor(($to - $from) / 2);

            // Split dataset and search in the right part
            if ($this->dataset[$position] >= $number) {
                $to = $position;
            } else {
                $from = $position;
            }
        } while ($to - $from > 1);  // Repeat until we find the number

        return $this->dataset[$from];
    }

    /**
     * Recursive binary search
     *
     * @param int $number
     * @param array $dataset
     * @return int
     */
    protected function recursiveBinarySearch(int $number, array $dataset): int
    {
        // If dataset has only one element
        if (count($dataset) === 1) {
            return $dataset[0];
        }

        // Get middle position
        $position = floor(count($dataset) / 2);

        // Split dataset and search in the right part
        return $dataset[$position] >= $number
            ? $this->recursiveBinarySearch($number, array_slice($dataset, 0, $position))
            : $this->recursiveBinarySearch($number, array_slice($dataset, $position));
    }

    /**
     * Test type of argument and out of boundaries
     * Return null if argument is valid and in boundaries
     * Return -1 if argument is invalid or out of lower boundaries
     * Return last element of dataset if argument is out of upper boundaries
     *
     * @param mixed $number
     * @return int|null
     */
    protected function testTypeAndRange(mixed $number): int|null
    {
        // Check if argument is a not integer or lower than first element of dataset
        if (!is_int($number) || $number <= $this->dataset[0]) {
            return -1;
        }

        // Check if argument is greater than last element of dataset
        if ($number > $this->dataset[count($this->dataset) - 1]) {
            return $this->dataset[count($this->dataset) - 1];
        }

        return null;
    }
}

