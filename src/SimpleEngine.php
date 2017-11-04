<?php

namespace FilippoToso\Recommendation;

use FilippoToso\Recommendation\Engine;
use FilippoToso\Recommendation\Util;

class SimpleEngine extends Engine
{

    /**
     * Add a new preference to the local storage
     * @method add_preference
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @param  Void
     */
    public function add_preference($user_id, $action_id) {

        $this->preference[$user_id] = isset($this->preference[$user_id]) ? $this->preference[$user_id] : [];

        if (!in_array($action_id, $this->preference[$user_id])) {
            $this->preference[$user_id][] = $action_id;
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

        if (($key = array_search($action_id, $this->preference[$user_id])) !== FALSE) {
            unset($this->preference[$user_id][$key]);
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

            foreach ($sets as $action) {
                $result[$action][] = $user_id;
            }

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

        $numerator = 0;
        foreach ($this->actions[$action_id] as $Nuser) {
            $numerator += Util::calculate_jaccard_similarity($this->preferences[$user_id], $this->preferences[$Nuser]);
        }

        $denominator = count($this->actions[$action_id]);

        return $numerator / $denominator;

    }

    /**
     * Checks if the provided action already exists in the preferences for the provided user
     * @method generate_actions_sets
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @return Boolean
     */
    protected function is_new($user_id, $action_id) {
        return !in_array($action_id, $this->preferences[$user_id]);
    }

}
