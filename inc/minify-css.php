<?php

$minificationStore = array();
function getNextMinificationPlaceholder() {
	global $minificationStore;
	return '<-!!-' . sizeof($minificationStore) . '-!!->';
}

abstract class MinificationSequenceFinder {
	public $start_idx;
	public $end_idx;
	public $type;
	abstract protected function findFirstValue($string);
	public function isValid() {
		return $this->start_idx !== false;
	}
}

class StringSequenceFinder extends MinificationSequenceFinder {
	protected $start_delimiter;
	protected $end_delimiter;
	function __construct($start_delimiter, $end_delimiter) {
		$this->type = $start_delimiter;
		$this->start_delimiter = $start_delimiter;
		$this->end_delimiter = $end_delimiter;
		}
	public function findFirstValue($string) {
		$this->start_idx = strpos($string, $this->start_delimiter);
		if ($this->isValid()) {
			$this->end_idx = strpos($string, $this->end_delimiter, $this->start_idx+1);
			// sanity check for non well formed lines
			$this->end_idx = ($this->end_idx === false ? strlen($string) : $this->end_idx + strlen($this->end_delimiter));
		}
	}
}

class QuoteSequenceFinder extends MinificationSequenceFinder {
	function __construct($type) {
		$this->type = $type;
	}
	public function findFirstValue($string) {
		$this->start_idx = strpos($string, $this->type);
		if ($this->isValid()) {
			// look for first non escaped endquote
			$this->end_idx = $this->start_idx+1;
			while ($this->end_idx < strlen($string)) {
				// find number of escapes before endquote
				if (preg_match('/(\\\\*)(' . preg_quote($this->type) . ')/', $string, $match, PREG_OFFSET_CAPTURE, $this->end_idx)) {
					$this->end_idx = $match[2][1] + 1;
					// if odd number of escapes before endquote, endquote is escaped. Keep going
					if (!isset($match[1][0]) || strlen($match[1][0]) % 2 == 0) {
						return;
					}
				} else {
					// no match, not well formed
					$this->end_idx = strlen($string);
					return;
				}
			}
		}
	}
}

function getNextSpecialSequence($string, $sequences) {
	// $special_idx is an array of the nearest index for all special characters
	$special_idx = array();
	foreach ($sequences as $finder) {
		// var_dump($sequences);
		if ( $finder !== null) {
			$finder->findFirstValue($string);
			if ($finder->isValid()) {
				$special_idx[$finder->start_idx] = $finder;
			}
		}
	}
	// if none found, return
	if (count($special_idx) == 0) {return false;}
	// get first occuring item
	asort($special_idx);
	return $special_idx[min(array_keys($special_idx))];
}