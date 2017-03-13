<?php
/**
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * file SubsCompat.php
 * Compatibility & Subroutines for the Portal
 *
 * @version 1.0 RC1
 */

if(!defined('PMX'))
	die('This file can\'t be run without PortaMx-Forum');

/**
* Replacement for http_build_query
*/
function pmx_http_build_query($data, $prefix = '', $sep = ';')
{
	$ret = array();
	foreach ((array) $data as $k => $v)
	{
		$k = urlencode($k);
		if(is_int($k) && !empty($prefix))
			$k = $prefix . $k;
		if(is_array($v) || is_object($v))
			array_push($ret, pmx_http_build_query($v, '', $sep));
		elseif($v == '')
			array_push($ret, $k);
		else
			array_push($ret, $k .'='. urlencode($v));
	}

	if(empty($sep))
		$sep = ini_get("arg_separator.output");

	return implode($sep, $ret);
}

/**
* Replacement for parse_url
*/
function pmx_parse_url($data, $component = '')
{
	return empty($component) ? parse_url($data) : parse_url($data, $component);
}

/**
* Read POST data with a specific key
* used for Blocks Pageindex
**/
function pmx_GetPostKey($postKey, &$result)
{
	global $modSettings;

	if(!empty($_POST[$postKey]))
	{
		if(!empty($modSettings['sef_enabled']))
			$_POST[$postKey] = pmxsef_query(str_replace('//', '/', $_POST[$postKey]));
		else
		{
			$tmp = $data = array();
			$_POST[$postKey] = explode(';', $_POST[$postKey]);
			while(list($key, $val) = each($_POST[$postKey]))
			{
				$tmp = explode('=', $val);
				$data[$tmp[0]] = isset($tmp[1]) ? $tmp[1] : '';
			}
			$_POST[$postKey] = $data;
		}
		$result = array_merge($result, $_POST[$postKey]);
		if(isset($result['pgkey']))
			unset($result['pgkey']);

		return $result;
	}
}

/**
* load customer CSS definitions.
* called from hook "integrate_pre_css_output"
**/
function PortaMx_loadCSS()
{
	global $context, $modSettings;

	if(!empty($context['pmx']['customCSS']))
	{
		$tmp = PortaMx_compressCSS($context['pmx']['customCSS']);
		if(isset($context['pmx']['customCSS']) && !empty($context['pmx']['customCSS']))
			echo '
	<style type="text/css">'."\n\t\t". PortaMx_compressCSS($context['pmx']['customCSS']) .'
	</style>';
	}
}

/**
* Ajax function php syntax check
*/
function PortaMx_PHPsyntax($data)
{
	// convert and cleanup the PHP string, then call the checker ..
	$cleanstr = html_entity_decode($data, ENT_QUOTES | ENT_XML1, 'UTF-8');
	$result = @php_syntax_error($cleanstr);

	// setup result
	if(empty($result))
		$line = '<b>No syntax errors detected.</b>';
	else
	{
		$errline = empty($result[1]) ? '1' : $result[1];
		$line = '<b>'. $result[0] .' on line: '. $errline .'</b>';
	}
	return $line;
}

/**
* php syntax check
*/
function php_syntax_error($code)
{
	$braces = 0;
	$inString = 0;

	// First of all, we need to know if braces are correctly balanced.
	// This is not trivial due to variable interpolation which
	// occurs in heredoc, backticked and double quoted strings
	foreach(token_get_all('<?php ' . $code) as $token)
	{
		if(is_array($token))
		{
			switch ($token[0])
			{
				case T_CURLY_OPEN:
				case T_DOLLAR_OPEN_CURLY_BRACES:
				case T_START_HEREDOC: ++$inString; break;
				case T_END_HEREDOC:   --$inString; break;
			}
		}
		else if($inString & 1)
		{
			switch($token)
			{
				case '`':
				case '"': --$inString; break;
			}
		}
		else
		{
			switch($token)
			{
			case '`':
			case '"': ++$inString; break;
			case '{': ++$braces; break;
			case '}':
				if($inString) --$inString;
				else
				{
					--$braces;
					if($braces < 0) break 2;
				}
				break;
			}
		}
	}

	// Display parse error messages and use output buffering to catch them
	$inString = @ini_set('log_errors', false);
	$token = @ini_set('display_errors', true);
	ob_start();

	// If $braces is not zero, then we are sure that $code is broken.
	// We run it anyway in order to catch the error message and line number.
	// Else, if $braces are correctly balanced, then we can safely put
	// $code in a dead code sandbox to prevent its execution.
	// Note that without this sandbox, a function or class declaration inside
	// $code could throw a "Cannot redeclare" fatal error.
	$braces || $code = "if(0){{$code}\n}";

	if(false === eval($code))
	{
		if($braces) $braces = PHP_INT_MAX;
			else
		{
			// Get the maximum number of lines in $code to fix a border case
			false !== strpos($code, "\r") && $code = strtr(str_replace("\r\n", "\n", $code), "\r", "\n");
			$braces = substr_count($code, "\n");
		}

		$code = ob_get_clean();
		$code = strip_tags($code);

		// Get the error message and line number
		if(preg_match("'syntax error, (.+) in .+ on line (\d+)$'s", $code, $code))
		{
			$code[2] = (int) $code[2];
			$code = $code[2] <= $braces
				? array($code[1], $code[2])
				: array('unexpected $end' . substr($code[1], 14), $braces);
		}
		else
			$code = array('syntax error', 0);
	}
	else
	{
		ob_end_clean();
		$code = '';
	}

	@ini_set('display_errors', $token);
	@ini_set('log_errors', $inString);
	return $code;
}
?>