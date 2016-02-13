<?php

class EpiTemplate {

    public function display($template = null, $vars = null) {
	if (is_array($vars)) {
	    extract($vars);
	}
	$templateInclude = Epi::getPath('view') . '/' . $template;
	if (is_file($templateInclude)) {
	    include $templateInclude;
	} else if (is_file($template)) {
	    include $template;
	} else {
	    EpiException::raise(new EpiException("Could not load template: {$templateInclude}", 404));
	}
    }

    public function get($template = null, $vars = null) {
	$templateInclude = Epi::getPath('view') . '/' . $template;
	if (is_file($templateInclude)) {
	    if (is_array($vars)) {
		extract($vars);
	    }
	    ob_start();
	    include $templateInclude;
	    $contents = ob_get_contents();
	    ob_end_clean();
	    return $contents;
	} else {
	    EpiException::raise(new EpiException("Could not load template: {$templateInclude}", 404));
	}
    }

    public function json($data) {
	if ($retval = json_encode($data)) { // TODO Find out if assigment is intended
	    return $retval;
	} else {
	    $dataDump = var_export($dataDump, 1);
	    EpiException::raise(new EpiException("json_encode failed for {$dataDump}", 404));
	}
    }

    public function jsonResponse($data) {
	$json = self::json($data);
	header('X-JSON: (' . $json . ')');
	header('Content-type: application/x-json');
	echo $json;
    }

}

function getTemplate() {
    static $template;
    if ($template) {
	return $template;
    }
    $template = new EpiTemplate();
    return $template;
}
