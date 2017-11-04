<?php

namespace FilippoToso\Recommendation;

use FilippoToso\Recommendation\Engine;
use FilippoToso\Recommendation\Util;

class BinaryEngine extends Engine
{

    /**
     * Add a new preference to the local storage
     * @method add_preference
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @param  String   $preference  'like' or 'dislike'
     * @param  Void
     */
    public function add_preference($user_id, $action_id, $preference = 'like') {

        if (!in_array($preference, ['like', 'dislike'])) {
            return FALSE;
        }

        $this->preference[$user_id][$preference] = isset($this->preference[$user_id][$preference]) ? $this->preference[$user_id][$preference] : [];

        if (!in_array($action_id, $this->preference[$user_id][$preference])) {
            $this->preference[$user_id][$preference][] = $action_id;
        }

        // Reset actions
        $this->actions = [];

        return TRUE;

    }

    /**
     * Remove a preference from the local storage
     * @method remove_preference
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @return Void
     */
    public function remove_preference($user_id, $action_id) {

        if (($key = array_search($action_id, $this->preference[$user_id]['like'])) !== FALSE) {
            unset($this->preference[$user_id]['like'][$key]);
        }

        if (($key = array_search($action_id, $this->preference[$user_id]['dislike'])) !== FALSE) {
            unset($this->preference[$user_id]['dislike'][$key]);
        }

        // Reset actions
        $this->actions = [];

        return TRUE;

    }

    /**
     * Generates the actions sets of like/dislike from an array of user like and dislike
     * @method generate_actions_sets
     * @param  Array   $users Array of liked / disliked actions
     * @return Array   An array of users that liked / disliked specific actions (grouped by actions)
     */
    protected static function generate_actions_sets($preferences) {

        $result = [];

        foreach ($preferences as $user_id => $sets) {

            foreach ($sets['like'] as $action) {
                $result[$action]['like'][] = $user_id;
            }

            foreach ($sets['dislike'] as $action) {
                $result[$action]['dislike'][] = $user_id;
            }

        }

        foreach ($result as $action => &$item) {
            $item['like']    = isset($item['like'])    ? $item['like']    : [];
            $item['dislike'] = isset($item['dislike']) ? $item['dislike'] : [];
        }

        return $result;

    }

    /**
     * Calculate the liking of a specific user for a specific action
     * @method liking
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @return Real     The liking between the user and the action (in the -1 to 1 range)
     */
    public function liking($user_id, $action_id) {

        // Recalculate actions
        $this->actions = empty($this->actions) ? Util::generate_actions_sets($this->preferences) : $this->actions;

        if (!isset($this->preferences[$user_id]) || !isset($this->actions[$action_id])) {
            return FALSE;
        }

        // Source: https://www.toptal.com/algorithms/predicting-likes-inside-a-simple-recommendation-engine

        $ZL = 0;
        foreach ($this->actions[$action_id]['like'] as $Zuser) {
            $ZL += Util::calculate_complex_similarity($this->preferences[$user_id]['like'], $this->preferences[$Zuser]['like'], $this->preferences[$user_id]['dislike'], $this->preferences[$Zuser]['dislike']);
        }

        $ZD = 0;
        foreach ($this->actions[$action_id]['dislike'] as $Zuser) {
            $ZD += Util::calculate_complex_similarity($this->preferences[$user_id]['like'], $this->preferences[$Zuser]['like'], $this->preferences[$user_id]['dislike'], $this->preferences[$Zuser]['dislike']);
        }

        $ML = count($this->actions[$action_id]['like']);
        $MD = count($this->actions[$action_id]['dislike']);

        return ($ZL - $ZD) / ($ML + $MD);

    }

    /**
     * Checks if the provided action already exists in the preferences for the provided user
     * @method generate_actions_sets
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @return Boolean
     */
    protected function is_new($user_id, $action_id) {
        return !(in_array($action_id, $this->preferences[$user_id]['like']) || in_array($action_id, $this->preferences[$user_id]['dislike']));
    }

}
