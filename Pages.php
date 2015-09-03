<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Common_Site {
    /**
     * Персональная страница менеджера
     */
    public function action_manager()
    {
        $id = $this->request->param('id');

        if (!$manager = Model::factory('Modules')->getManagerData($id)) {
            throw HTTP_Exception::factory(404, 'Страница не найдена');
        }

        // Голосование.
        if ($this->request->method() == 'POST') {
            if ($this->request->is_ajax()) {
                if (isset($_SERVER['REMOTE_ADDR']) && Rating::allowVotes($id, $_SERVER['REMOTE_ADDR'])) {
                    $type = Arr::get($_POST, 'vote', '+');
                    Rating::vote($id, $type);
                }
            }
        }

        $managers = Model::factory('Modules')->getManagers();

        $sidebar = View::factory('parts/contacts/sidebar')
            ->bind('region', $this->region);

        $this->template->content = View::factory('parts/contacts/manager')
            ->bind('managers', $managers)
            ->bind('manager', $manager)
            ->bind('sidebar', $sidebar);
    }
}
