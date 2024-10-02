<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model {

	protected $appends = ['variables', 'dynamic_body'];
	protected $guarded = [];

	public function scopeBySubject($query, $type) {
		return $query->where('title', '=', trim($type));
	}

	public function createEmailBody($searchReplace) {
		$message = $this->replaceVariables($searchReplace);

		$message = nl2br($message);
		$message = $message . ' ' . $this->dynamic_body;

		$message = html_entity_decode(utf8_decode($message));

		return $message;
	}

	public function createSMSBody($searchReplace) {
		$message = $this->replaceVariables($searchReplace);

		//$message = $message . ' ' . $this->dynamic_body;

		$message = nl2br($message);
		$message = html_entity_decode(utf8_decode($message));

		return $message;
	}

	public function replaceVariables($searchReplace) {

		$message = $this->static_body;

		foreach ($searchReplace as $key => $value) {

			$message = str_replace($key, $value, $message);
		}

		return $message;

	}

	/**
	 * @return array
	 */
	public function getVariablesAttribute() {
		if ($this->static_body) {

			preg_match_all('#\[(.*?)\]#', $this->static_body, $match);
			return $match[0];
		}

		return [];
	}

	/**
	 * @return mixed|string
	 */
	public function getEmailBody($searchReplace, $proposed_variables = null) {
		$this->dynamic_body = $this->createEmailBody($searchReplace) . $proposed_variables;
		
		return $this->dynamic_body;
	}

	public function getSmsBody($searchReplace, $proposed_variables = null) {
		$this->dynamic_body = $this->createSMSBody($searchReplace) . $proposed_variables;

		return $this;
	}

}
