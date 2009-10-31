<?php
/**
 * cssmin.php - A simple CSS minifier.
 * --
 * 
 * <code>
 * include("cssmin.php");
 * file_put_contents("path/to/target.css", cssmin::minify(file_get_contents("path/to/source.css")));
 * </code>
 * --
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING 
 * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * --
 *
 * @package 	cssmin
 * @author 		Joe Scylla <joe.scylla@gmail.com>
 * @copyright 	2008 Joe Scylla <joe.scylla@gmail.com>
 * @license 	http://opensource.org/licenses/mit-license.php MIT License
 * @version 	1.0.1.b3 (2008-10-02)
 */
class cssmin
	{
	/**
	 * Minifies stylesheet definitions
	 *
	 * <code>
	 * $css_minified = cssmin::minify(file_get_contents("path/to/target/file.css"));
	 * </code>
	 * 
	 * @param	string			$css		Stylesheet definitions as string
	 * @param	array|string	$options	Array or comma speperated list of options:
	 * 										
	 * 										- remove-last-semicolon: Removes the last semicolon in 
	 * 										the style definition of an element (activated by default).
	 * 										
	 * 										- preserve-urls: Preserves every url defined in an url()-
	 * 										expression. This option is only required if you have 
	 * 										defined really uncommon urls with multiple spaces or 
	 * 										combination of colon, semi-colon, braces with leading or 
	 * 										following spaces.
	 * @return	string			Minified stylesheet definitions
	 */
	public static function minify($css, $options = "remove-last-semicolon")
		{
		$options = ($options == "") ? array() : (is_array($options) ? $options : explode(",", $options));
		if (in_array("preserve-urls", $options))
			{
			// Encode url() to base64
			$css = preg_replace_callback("/url\s*\((.*)\)/siU", "cssmin_encode_url", $css);
			}
		// Remove comments
		$css = preg_replace("/\/\*[\d\D]*?\*\/|\t+/", " ", $css);
		// Replace CR, LF and TAB to spaces
		$css = str_replace(array("\n", "\r", "\t"), " ", $css);
		// Replace multiple to single space
		$css = preg_replace("/\s\s+/", " ", $css);
		// Remove unneeded spaces
		$css = preg_replace("/\s*({|}|\[|\]|=|~|\+|>|\||;|:|,)\s*/", "$1", $css);
		if (in_array("remove-last-semicolon", $options))
			{
			// Removes the last semicolon of every style definition
			$css = str_replace(";}", "}", $css);
			}
		$css = trim($css);
		if (in_array("preserve-urls", $options))
			{
			// Decode url()
			$css = preg_replace_callback("/url\s*\((.*)\)/siU", "cssmin_encode_url", $css);
			}
		return $css;
		}
	/**
	 * Return a array structure of a stylesheet definitions.
	 *
	 * <code>
	 * $css_structure = cssmin::toArray(file_get_contents("path/to/target/file.css"));
	 * </code>
	 * 
	 * @param	string		$css			Stylesheet definitions as string
	 * @param	string		$options		Options for {@link cssmin::minify()}
	 * @return	array						Structure of the stylesheet definitions as array
	 */
	public static function toArray($css, $options = "")
		{
		$r = array();
		$css = cssmin::minify($css, $options);
		preg_match_all("/(.+){(.+:.+);}/U", $css, $items);
		if (count($items[0]) > 0)
			{
			for ($i = 0; $i < $c = count($items[0]); $i++)
				{
				$keys		= explode(",", $items[1][$i]);
				$styles_tmp	= explode(";", $items[2][$i]);
				$styles = array();
				foreach ($styles_tmp as $style)
					{
					$style_tmp = explode(":", $style);
					$styles[$style_tmp[0]] = $style_tmp[1];
					}
				$r[] = array
					(
					"keys"		=> cssmin_array_clean($keys),
					"styles"	=> cssmin_array_clean($styles)
					);
				}
			}
		return $r;
		}
	/**
	 * Return a array structure created by {@link cssmin::toArray()} to a string.
	 *
	 * <code>
	 * $css_string = cssmin::toString($css_structure);
	 * </code>
	 * 
	 * @param	array		$css
	 * @return	array
	 */
	public static function toString(array $array)
		{
		$r = "";
		foreach ($array as $item)
			{
			$r .= implode(",", $item["keys"]) . "{";
			foreach ($item["styles"] as $key => $value)
				{
				$r .= $key . ":" . $value . ";";
				}
			$r .= "}";
			}
		return $r;
		}
	}

/**
 * Trims all elements of the array and removes empty elements. 
 *
 * @param	array		$array
 * @return	array
 */
function cssmin_array_clean(array $array)
	{
	$r = array();
	$c = count($v);
	if (cssmin_array_is_assoc($array))
		{
		foreach ($array as $key => $value)
			{
			$r[$key] = trim($value);
			}
		}
	else
		{
		foreach ($array as $value)
			{
			if (trim($value) != "")
				{
				$r[] = trim($value);
				}
			}
		}
	return $r;
	}
/**
 * Return if a value is a associative array.
 *
 * @param	array		$array
 * @return	bool
 */
function cssmin_array_is_assoc($array)
	{
	if (!is_array($array))
		{
		return false;
		}
	else
		{
		krsort($array, SORT_STRING);
		return !is_numeric(key($array));
		}
	}
/**
 * Encodes a url() expression.
 *
 * @param	array	$match
 * @return	string
 */
function cssmin_encode_url($match)
	{
	return "url(" . base64_encode(trim($match[1])) . ")";
	}
/**
 * Decodes a url() expression.
 *
 * @param	array	$match
 * @return	string
 */
function cssmin_decode_url($match)
	{
	return "url(" . base64_decode($match[1]) . ")";
	}

/**
 * jsmin.php - PHP implementation of Douglas Crockford's JSMin.
 *
 * This is pretty much a direct port of jsmin.c to PHP with just a few
 * PHP-specific performance tweaks. Also, whereas jsmin.c reads from stdin and
 * outputs to stdout, this library accepts a string as input and returns another
 * string as output.
 *
 * PHP 5 or higher is required.
 *
 * Permission is hereby granted to use this version of the library under the
 * same terms as jsmin.c, which has the following license:
 *
 * --
 * Copyright (c) 2002 Douglas Crockford  (www.crockford.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * The Software shall be used for Good, not Evil.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * --
 *
 * @package JSMin
 * @author Ryan Grove <ryan@wonko.com>
 * @copyright 2002 Douglas Crockford <douglas@crockford.com> (jsmin.c)
 * @copyright 2008 Ryan Grove <ryan@wonko.com> (PHP port)
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.1.1 (2008-03-02)
 * @link http://code.google.com/p/jsmin-php/
 */

class JSMin {
  const ORD_LF    = 10;
  const ORD_SPACE = 32;

  protected $a           = '';
  protected $b           = '';
  protected $input       = '';
  protected $inputIndex  = 0;
  protected $inputLength = 0;
  protected $lookAhead   = null;
  protected $output      = '';

  // -- Public Static Methods --------------------------------------------------

  public static function minify($js) {
    $jsmin = new JSMin($js);
    return $jsmin->min();
  }

  // -- Public Instance Methods ------------------------------------------------

  public function __construct($input) {
    $this->input       = str_replace("\r\n", "\n", $input);
    $this->inputLength = strlen($this->input);
  }

  // -- Protected Instance Methods ---------------------------------------------

  protected function action($d) {
    switch($d) {
      case 1:
        $this->output .= $this->a;

      case 2:
        $this->a = $this->b;

        if ($this->a === "'" || $this->a === '"') {
          for (;;) {
            $this->output .= $this->a;
            $this->a       = $this->get();

            if ($this->a === $this->b) {
              break;
            }

            if (ord($this->a) <= self::ORD_LF) {
              throw new JSMinException('Unterminated string literal.');
            }

            if ($this->a === '\\') {
              $this->output .= $this->a;
              $this->a       = $this->get();
            }
          }
        }

      case 3:
        $this->b = $this->next();

        if ($this->b === '/' && (
            $this->a === '(' || $this->a === ',' || $this->a === '=' ||
            $this->a === ':' || $this->a === '[' || $this->a === '!' ||
            $this->a === '&' || $this->a === '|' || $this->a === '?')) {

          $this->output .= $this->a . $this->b;

          for (;;) {
            $this->a = $this->get();

            if ($this->a === '/') {
              break;
            } elseif ($this->a === '\\') {
              $this->output .= $this->a;
              $this->a       = $this->get();
            } elseif (ord($this->a) <= self::ORD_LF) {
              throw new JSMinException('Unterminated regular expression '.
                  'literal.');
            }

            $this->output .= $this->a;
          }

          $this->b = $this->next();
        }
    }
  }

  protected function get() {
    $c = $this->lookAhead;
    $this->lookAhead = null;

    if ($c === null) {
      if ($this->inputIndex < $this->inputLength) {
        $c = $this->input[$this->inputIndex];
        $this->inputIndex += 1;
      } else {
        $c = null;
      }
    }

    if ($c === "\r") {
      return "\n";
    }

    if ($c === null || $c === "\n" || ord($c) >= self::ORD_SPACE) {
      return $c;
    }

    return ' ';
  }

  protected function isAlphaNum($c) {
    return ord($c) > 126 || $c === '\\' || preg_match('/^[\w\$]$/', $c) === 1;
  }

  protected function min() {
    $this->a = "\n";
    $this->action(3);

    while ($this->a !== null) {
      switch ($this->a) {
        case ' ':
          if ($this->isAlphaNum($this->b)) {
            $this->action(1);
          } else {
            $this->action(2);
          }
          break;

        case "\n":
          switch ($this->b) {
            case '{':
            case '[':
            case '(':
            case '+':
            case '-':
              $this->action(1);
              break;

            case ' ':
              $this->action(3);
              break;

            default:
              if ($this->isAlphaNum($this->b)) {
                $this->action(1);
              }
              else {
                $this->action(2);
              }
          }
          break;

        default:
          switch ($this->b) {
            case ' ':
              if ($this->isAlphaNum($this->a)) {
                $this->action(1);
                break;
              }

              $this->action(3);
              break;

            case "\n":
              switch ($this->a) {
                case '}':
                case ']':
                case ')':
                case '+':
                case '-':
                case '"':
                case "'":
                  $this->action(1);
                  break;

                default:
                  if ($this->isAlphaNum($this->a)) {
                    $this->action(1);
                  }
                  else {
                    $this->action(3);
                  }
              }
              break;

            default:
              $this->action(1);
              break;
          }
      }
    }

    return $this->output;
  }

  protected function next() {
    $c = $this->get();

    if ($c === '/') {
      switch($this->peek()) {
        case '/':
          for (;;) {
            $c = $this->get();

            if (ord($c) <= self::ORD_LF) {
              return $c;
            }
          }

        case '*':
          $this->get();

          for (;;) {
            switch($this->get()) {
              case '*':
                if ($this->peek() === '/') {
                  $this->get();
                  return ' ';
                }
                break;

              case null:
                throw new JSMinException('Unterminated comment.');
            }
          }

        default:
          return $c;
      }
    }

    return $c;
  }

  protected function peek() {
    $this->lookAhead = $this->get();
    return $this->lookAhead;
  }
}

// -- Exceptions ---------------------------------------------------------------
class JSMinException extends Exception {}
?>
