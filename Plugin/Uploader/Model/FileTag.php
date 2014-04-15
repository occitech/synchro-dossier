<?php

App::uses('ScopedTaxonomy', 'Uploader.Model');

class FileTag extends ScopedTaxonomy {

	private $__vocabularyAlias = 'file-tags';

	protected function _getVocabularyAlias() {
		return $this->__vocabularyAlias;
	}

}
