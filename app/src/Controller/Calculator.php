<?php

namespace App\Controller;

const DELIMITER_PREFIX = '//';
const DEFAULT_DELIMITER = ',';

class Calculator
{
    public function add(string $text)
    {
        $delimiter = $this->getDelimiter($text);
        $splitted = preg_split('~(?:'.$delimiter."|\n)~", $text);

        $toNumbers = array_map([$this, 'convertStringToInt'], $splitted);

        return array_reduce($toNumbers, [$this, 'addTwoNumbers']);
    }

    private function getDelimiter(string $text) {
        $customDelimiter = preg_grep('~^('.DELIMITER_PREFIX.'.+)$~', [strtok($text, "\n")]);
        if ($customDelimiter && 0 < count($customDelimiter)) {
            return preg_quote(preg_replace('~'.DELIMITER_PREFIX.'~', '', $customDelimiter[0]));
        }

        return DEFAULT_DELIMITER;
    }

    private function addTwoNumbers($a, $b)
    {
        return $a + $b;
    }

    private function convertStringToInt($a)
    {
        $converted = intval($a);
        if ($converted < 0) {
            throw new \ErrorException('negatives not allowed');
        }

        if ($converted > 1000) {
            return 0;
        }

        return $converted;
    }
}
