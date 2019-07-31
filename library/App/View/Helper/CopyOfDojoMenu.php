<?php

class App_View_Helper_DojoMenu extends Zend_View_Helper_Navigation_Menu
{
    public function DojoMenu(Zend_Navigation_Container $container = null)
    {
        if (null !== $container) {
            $this->setContainer($container);
        }
        return $this;
    }

    public function htmlify(Zend_Navigation_Page $page)
    {
        return;
        $label = $page->getLabel();
        // does page have subpages?
        if ($page->count()) {
            $sub_indicator = '<span class="sf-sub-indicator"> »</span>';
            $attribs['class'] .= ' sf-with-ul';
        } else {
            $sub_indicator = '';
        }
        return '<div class="dojo-TreeNode" title="' . $this->view->escape($label) . '">'
            . '</div>';
    }

    protected function _renderDeepestMenu(
        Zend_Navigation_Container $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth
    ) {
        if (!$active = $this->findActive($container, $minDepth - 1, $maxDepth)) {
            return '';
        }

        // special case if active page is one below minDepth
        if ($active['depth'] < $minDepth) {
            if (!$active['page']->hasPages()) {
                return '';
            }
        } else {
            if (!$active['page']->hasPages()) {
                // found pages has no children; render siblings
                $active['page'] = $active['page']->getParent();
            } else {
                if (is_int($maxDepth) && $active['depth'] + 1 > $maxDepth) {
                    // children are below max depth; render siblings
                    $active['page'] = $active['page']->getParent();
                }
            }
        }

        $ulClass = $ulClass ? ' class="' . $ulClass . '"' : '';
        $html = $indent . '<div class="dojo-TreeNode">' . self::EOL;

        foreach ($active['page'] as $subPage) {
            if (!$this->accept($subPage)) {
                continue;
            }
            $liClass = $subPage->isActive(true) ? ' class="active"' : '';
            $html .= $indent . '    <div class="dojo-TreeNode" title="' . $subPage->getLabel() . '">' . self::EOL;
            //$html .= $indent . '        ' . $this->htmlify($subPage) . self::EOL;
            $html .= $indent . '    </div>' . self::EOL;
        }

        $html .= $indent . '</div>';

        return $html;
    }

    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * @param Zend_Navigation_Container $container container to render
     * @param string $ulClass CSS class for first UL
     * @param string $indent initial indentation
     * @param int|null $minDepth minimum depth
     * @param int|null $maxDepth maximum depth
     * @param bool $onlyActive render only active branch?
     * @return string
     */
    protected function _renderMenu(
        Zend_Navigation_Container $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $onlyActive
    ) {
        $html = '';

        // find deepest active
        if ($found = $this->findActive($container, $minDepth, $maxDepth)) {
            $foundPage = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibilty
                continue;
            } else {
                if ($onlyActive && !$isActive) {
                    // page is not active itself, but might be in the active branch
                    $accept = false;
                    if ($foundPage) {
                        if ($foundPage->hasPage($page)) {
                            // accept if page is a direct child of the active page
                            $accept = true;
                        } else {
                            if ($foundPage->getParent()->hasPage($page)) {
                                // page is a sibling of the active page...
                                if (!$foundPage->hasPages() ||
                                    is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                                    // accept if active page has no children, or the
                                    // children are too deep to be rendered
                                    $accept = true;
                                }
                            }
                        }
                    }

                    if (!$accept) {
                        continue;
                    }
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth == 0) {
                    $ulClass = ' class="' . $ulClass . '"';
                } else {
                    $ulClass = '';
                }
                $html .= $myIndent . '<div class="dojo-Tree">' . self::EOL;
            } else {
                if ($prevDepth > $depth) {
                    // close li/ul tags until we're at current depth
                    for ($i = $prevDepth; $i > $depth; $i--) {
                        $ind = $indent . str_repeat('        ', $i);
                        $html .= $ind . '    </div>' . self::EOL;
                        $html .= $ind . '</div>' . self::EOL;
                    }
                    // close previous li tag
                    $html .= $myIndent . '    </div>' . self::EOL;
                } else {
                    // close previous li tag
                    $html .= $myIndent . '    </div>' . self::EOL;
                }
            }

            // render li tag and page
            $liClass = $isActive ? ' class="active"' : '';
            $html .= $myIndent . '    <div class="dojo-TreeNode" title="' . $page->getLabel() . '">' . self::EOL
                . $myIndent . '        ' . $this->htmlify($page) . self::EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth + 1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i - 1);
                $html .= $myIndent . '    </div>' . self::EOL
                    . $myIndent . '</div>' . self::EOL;
            }
            $html = rtrim($html, self::EOL);
        }

        return $html;
    }

}