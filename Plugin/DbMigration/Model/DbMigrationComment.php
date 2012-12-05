<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');

class DbMigrationComment extends DbMigrationAppModel {

	public $useDbConfig = 'old';

	public $useTable = 'files_comments';

	public $primaryKey = 'comment_id';

}
