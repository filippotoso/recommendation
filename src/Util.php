<?php

namespace FilippoToso\Recommendation;

class Util
{

    /**
     * Calculates the unique array intercept between 2 arrays
     * @method unique_array_intersect
     * @param  Array  $A  First array
     * @param  Array  $B  Second array
     * @return Array
     */
    public static function unique_array_intersect($A, $B) {
        return array_unique(array_intersect($A, $B));
    }

    /**
     * Calculates the unique array union between multiple arrays
     * @method unique_array_union
     * @return Array
     */
    public static function unique_array_union() {
        $array = [];
        foreach (func_get_args() as $item) {
            $array = array_merge($array, $item);
        }
        return array_unique($array);
    }

    /**
     * Calculates the Jaccard Index formula between 2 arrays
     * @method calculate_jaccard_index
     * @param  Array   $A The first array
     * @param  Array   $B The second array
     * @return Real    The index value between 0 and 1
     */
    public static function calculate_jaccard_similarity($A, $B) {
        return count(self::unique_array_intersect($A, $B)) / count(self::unique_array_union($A, $B));
    }

    /**
     * Calculates the complex similarity between 2 sets of like ($L1 and $L2) and dislike ($D1 and $D2)
     * @method calculate_complex_similarity
     * @param  Array   $L1 The first set of like
     * @param  Array   $L2 The second set of like
     * @param  Array   $D1 The first set of dislike
     * @param  Array   $D2 The second set of dislike
     * @return Real    The index value between -1 and 1
     */
    public static function calculate_complex_similarity($L1, $L2, $D1, $D2) {

        $numerator = count(self::unique_array_intersect($L1, $L2)) +
                     count(self::unique_array_intersect($D1, $D2)) +
                     count(self::unique_array_intersect($L1, $D2)) -
                     count(self::unique_array_intersect($L2, $D1));

        $denominator = count(self::unique_array_union($L1, $L2, $D1, $D2));

        return $numerator / $denominator;

    }

}
