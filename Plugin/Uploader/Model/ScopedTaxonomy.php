<?php
App::uses('Taxonomy', 'Taxonomy.Model');

abstract class ScopedTaxonomy extends Taxonomy {

	public $useTable = 'taxonomies';

	private $__vocabularyId = null;

	abstract protected function _getVocabularyAlias();

	public function beforeFind($query) {
		$result = parent::beforeFind($query);
		if (!is_bool($result)) {
			$query = $result;
		}
		if ($query) {
			$query['conditions'][$this->escapeField('vocabulary_id')] = $this->__relatedVocabularyId();
		}
		return $query;
	}

	public function beforeSave($options = array()) {
		$this->data[$this->alias]['vocabulary_id'] = $this->__relatedVocabularyId();

		return parent::beforeSave($options);
	}

	public function getTree($options = array()) {
		return parent::getTree($this->_getVocabularyAlias(), $options);
	}

	public function getList($options = array()) {
		$options['contain'] = array('Term');
		return Hash::combine(
			$this->find('all', $options),
			sprintf('{n}.%s.id', $this->alias),
			'{n}.Term.title'
		);
	}

	protected function _idFromSlug($termSlug) {
		$termId = $this->Term->field('id', array('slug' => $termSlug));
		$id = $this->termInVocabulary($termId, $this->__relatedVocabularyId());
		if ($id === false) {
			throw new NotFoundException(__('The slug %s does not belong to a Term of this Taxonomy', $termSlug));
		}
		return $id;
	}

	private function __relatedVocabularyId() {
		if (is_null($this->__vocabularyId)) {
			$this->__vocabularyId = $this->Vocabulary->field(
				$this->Vocabulary->primaryKey,
				array('alias' => $this->_getVocabularyAlias())
			);
		}
		return $this->__vocabularyId;
	}

	public function termInVocabulary($termId) {
		return parent::termInVocabulary($termId, $this->__relatedVocabularyId());
	}

}
