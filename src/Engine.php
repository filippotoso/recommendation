<?php

namespace FilippoToso\Recommendation;

abstract class Engine
{

    protected $preferences = [];
    protected $actions = [];
    protected $options = ['new' => FALSE, 'sort' => FALSE];

    /**
     * Set / get engine options
     * @method option
     * @param  String       $name  new => get only new recommendations, sort => return asorted results
     * @param  Boolean      $value The new value of the option. If null returns the option value
     * @return Boolean|null Returns null in case of error
     */
    public function option($name, $value = null) {

        if (!isset($this->options[$name])) {
            return null;
        }

        if (is_null($value)) {
            return $this->options[$name];
        }

        $this->options[$name] = (bool) $value;

    }

    /**
     * Bulk loads preferences in the local storage
     * @method bulk_load
     * @param  Array    $preferences Array of liked / disliked action_ids by user_id
     * @return Void
     */
    public function bulk_load($preferences) {
        $this->preferences = $preferences;
    }

    /**
     * Get a copy of the preferences array
     * @method preferences
     * @return Array
     */
    public function preferences() {
        return $this->preferences;
    }

    /**
     * Prepare the internal actions from the preferences
     * @method prepare
     * @return Void
     */
    public function prepare() {
        $this->actions = static::generate_actions_sets($this->preferences);
    }

    /**
     * Calculate the recommendations for a single user
     * @method single_recommendations
     * @param  String   $user_id The user ID
     * @return Array    The recommendations for the provided user
     */
    protected function single_recommendations($user_id) {

        if (!isset($this->preferences[$user_id])) {
            return FALSE;
        }

        $actions = array_keys($this->actions);

        $result = [];

        foreach ($actions as $action_id) {

            if ($this->options['new'] && !$this->is_new($user_id, $action_id)) {
                continue;
            }

            $result[$action_id] = $this->liking($user_id, $action_id);

        }
        
        if ($this->options['sort']) {
            arsort($result);
        }

        return $result;

    }

    /**
     * Calculate the recommendations for a single user or all the users in the preferences
     * @method recommendations
     * @param  String|null   $user_id The user ID or null to calculate all the recommendations
     * @return Array         The calculated recommendations
     */
    public function recommendations($user_id = null) {

        // Recalculate actions
        $this->actions = empty($this->actions) ? static::generate_actions_sets($this->preferences) : $this->actions;

        if (!is_null($user_id)) {
            return $this->single_recommendations($user_id);
        }

        $actions = array_keys($this->actions);
        $users = array_keys($this->preferences);

        $result = [];

        foreach ($users as $user_id) {

            foreach ($actions as $action_id) {

                if ($this->options['new'] && !$this->is_new($user_id, $action_id)) {
                    continue;
                }

                $result[$user_id][$action_id] = $this->liking($user_id, $action_id);

            }

            if ($this->options['sort']) {
                arsort($result[$user_id]);
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
    abstract public function liking($user_id, $action_id);

    // TODO: Update with preferences

    /**
     * Generates the actions sets of like/dislike from an array of user like and dislike
     * @method generate_actions_sets
     * @param  Array   $users Array of liked / disliked actions
     * @return Array   An array of users that liked / disliked specific actions (grouped by actions)
     */
    abstract protected static function generate_actions_sets($preferences);

    /**
     * Checks if the provided action already exists in the preferences for the provided user
     * @method generate_actions_sets
     * @param  String   $user_id    The user ID
     * @param  String   $action_id  The action ID
     * @return Boolean
     */
    abstract protected function is_new($user_id, $action_id);

}
