<?php

namespace App\Traits;

/**
 * Trait ParserAble
 * @package App\Traits
 */
trait ParserAble {

    /**
     * @param $search
     *
     * @return array
     */
    public function parserSearchData($search): array {
        $searchData = [];

        if (stripos($search ?? '', ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }
}
