<?php
/**
 * Markdown  -  A text-to-HTML conversion tool for web writers
 *
 * @package   php-markdown
 * @author    Michel Fortin <michel.fortin@michelf.com>
 * @copyright 2004-2018 Michel Fortin <https://michelf.com/projects/php-markdown/>
 * @copyright (Original Markdown) 2004-2006 John Gruber <https://daringfireball.net/projects/markdown/>
 */

namespace Michelf;

/**
 * Markdown Parser Interface
 */
interface MarkdownInterface
{
    /**
     * Initialize the parser and return the result of its transform method.
     * This will work fine for derived classes too.
     *
     * @param string $text
     * @return string
     * @api
     *
     */
    public static function defaultTransform($text);

    /**
     * Main function. Performs some preprocessing on the input text
     * and pass it through the document gamut.
     *
     * @param string $text
     * @return string
     * @api
     *
     */
    public function transform($text);
}
