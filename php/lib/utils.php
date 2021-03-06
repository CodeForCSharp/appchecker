<?php
namespace AppChecker\Utils;

/**
 * Include file
 * @param string $path
 */
function IncludeFile($path) {
	global $zbp;
	if (is_readable($path)) {
		require $path;
		return true;
	} else {
		return false;
	}
}
/**
 * Get Function Description 
 */
function GetFunctionDescription($function) {
	try	{
		return new \ReflectionFunction($function);
	} catch (\ReflectionException $e) {
		echo $e->getMessage();
		return false;
	}

}
/**
 * Extracts all global variables as references and includes the file.
 * Useful for including legacy plugins.
 *
 * @param string $__filename__ File to include
 * @param array  $__vars__     Extra variables to extract into local scope
 * @throws Exception
 * @return void
 */
function GlobalInclude($__filename__, &$__vars__ = null) {
	if (!is_file($__filename__)) {
		throw new Exception('File ' . $__filename__ . ' does not exist');
	}

	extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
	if ($__vars__ !== null) {
		extract($__vars__, EXTR_REFS);
	}

	unset($__vars__);
	include $__filename__;
	unset($__filename__);
	foreach (array_diff_key(get_defined_vars(), $GLOBALS) as $key => $val) {
		$GLOBALS[$key] = $val;
	}
}

function ScanDirectory($path) {
	$ret = [];
	$dir = new \RecursiveDirectoryIterator($path);
	$iterator = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);

	foreach ($iterator as $name => $object) {
		if (!$object->isDir()) {
			array_push($ret, $object->getPathName());
		}
	}

	return $ret;
}
