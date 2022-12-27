<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_phocadownload
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;

class PhocaDownloadRouterrules extends MenuRules
{
	public function preprocess(&$query) {
		parent::preprocess($query);
	}

	protected function buildLookup($language = '*') {
		parent::buildLookup($language);
	}

    /*
     * PHOCAEDIT
     */
    public function parse(&$segments, &$vars) {

        // SPECIFIC CASE TAG - tag search output in category view
        // 1. components/com_phocadownload/router.php getCategorySegment() - BUILD
        // 2. administrator/components/com_phocadownload/libraries/phocadownload/path/routerrules.php build() - BUILD
        // 3. administrator/components/com_phocadownload/libraries/phocadownload/path/routerrules.php parse() - PARSE
        $app    = Factory::getApplication();
        $tagId  = $app->input->get('tagid', 0, 'int');
        if ($segments[0] == 'category' && (int)$tagId > 0){
            // We are in category view but tags output
            $vars['view'] = 'category';
            unset($segments[0]);
        }
        return parent::parse($segments, $vars);
    }

    /* EDIT of libraries/src/Component/Router/Rules/StandardRules.php build function
     * Because we need to manage when categories view does not have any ID
     * PHOCAEDIT
     */
    public function build(&$query, &$segments) {

		if (!isset($query['Itemid'], $query['view'])) {
			return;
		}

		// Get the menu item belonging to the Itemid that has been found
		$item = $this->router->menu->getItem($query['Itemid']);

		if ($item === null || $item->component !== 'com_' . $this->router->getName() || !isset($item->query['view'])) {
			return;
		}

        // PHOCAEDIT
        if (!isset($item->query['id']) ){
            $item->query['id'] = 0;
        }

		// Get menu item layout
		$mLayout = isset($item->query['layout']) ? $item->query['layout'] : null;

		// Get all views for this component
		$views = $this->router->getViews();


		// Return directly when the URL of the Itemid is identical with the URL to build
		if ($item->query['view'] === $query['view']) {
			$view = $views[$query['view']];

			if (!$view->key) {
				unset($query['view']);

				if (isset($query['layout']) && $mLayout === $query['layout']) {
					unset($query['layout']);
				}

				return;
			}

			if (isset($query[$view->key]) && $item->query[$view->key] == (int) $query[$view->key]) {
				unset($query[$view->key]);

				while ($view) {
					unset($query[$view->parent_key]);

					$view = $view->parent;
				}

				unset($query['view']);

				if (isset($query['layout']) && $mLayout === $query['layout']) {
					unset($query['layout']);
				}

				return;
			}
		}

		// Get the path from the view of the current URL and parse it to the menu item
		$path  = array_reverse($this->router->getPath($query), true);
		$found = false;

		foreach ($path as $element => $ids) {
			$view = $views[$element];

			if ($found === false && $item->query['view'] === $element) {
				if ($view->nestable) {
					$found = true;
				} elseif ($view->children) {
					$found = true;

					continue;
				}
			}

			if ($found === false) {
				// Jump to the next view
				continue;
			}

			if ($ids) {

				if ($view->nestable) {
					$found2 = false;

					foreach (array_reverse($ids, true) as $id => $segment) {

                        if ($found2) {
							$segments[] = str_replace(':', '-', $segment);

						} elseif ((int) $item->query[$view->key] === (int) $id) {
							$found2 = true;

                            // SPECIFIC CASE TAG - tag search output in category view
                            // 1. components/com_phocadownload/router.php getCategorySegment() - BUILD
                            // 2. administrator/components/com_phocadownload/libraries/phocadownload/path/routerrules.php build() - BUILD
                            // 3. administrator/components/com_phocadownload/libraries/phocadownload/path/routerrules.php parse() - PARSE
                            if ((int)$query['id'] == 0 && $query['view'] == 'category' && isset($query['tagid']) && (int)$query['tagid'] > 0) {
                                $segments[] = 'category';
                            }
						}
					}
				} elseif ($ids === true) {
					$segments[] = $element;
				} else {
					$segments[] = str_replace(':', '-', current($ids));
				}
			}

			if ($view->parent_key) {
				// Remove parent key from query
				unset($query[$view->parent_key]);
			}
		}

		if ($found) {
			unset($query[$views[$query['view']]->key], $query['view']);

			if (isset($query['layout']) && $mLayout === $query['layout']) {
				unset($query['layout']);
			}
		}
	}
}
