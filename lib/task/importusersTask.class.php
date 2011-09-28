<?php

class importusersTask extends sfBaseTask
{
	protected function configure()
	{
		// // add your own arguments here
		// $this->addArguments(array(
		//   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
		// ));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
		new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
		new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
		// add your own options here
		));

		$this->namespace        = '';
		$this->name             = 'import-users';
		$this->briefDescription = 'import users from LDAP to sf_gurad_user.';
		$this->detailedDescription = <<<EOF
The [import-users|INFO] task does things.
Call it with:

  [php symfony import-users|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		// initialize the database connection
		$databaseManager = new sfDatabaseManager($this->configuration);
		$connection = $databaseManager->getDatabase($options['connection'])->getConnection();

		// add your code here
		$c = new LDAPCriteria();
		$c->add('cn', '*');
		$c->addOr('uid', '*');
		$results = UserPeer::doSelect($c);
		$ajouter = 0;
		foreach($results as $user){
			$sfUserObj = Doctrine::getTable('SfGuardUser')->findOneBy('username', $user->get('uid'));
			if(!is_object($sfUserObj))
			{
				$sfUserObj = new sfGuardUser();
				$sfUserObj->setUsername($user->get('uid'));
				$sfUserObj->setIsActive(1);
				$sfUserObj->save();
				$ajouter ++;
			}
			 
		}
		echo count($results)." trouvé(s). \n";
		echo $ajouter." ajouté(s). \n";

	}
}
