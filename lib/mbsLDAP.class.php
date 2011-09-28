<?php
/**
 * Authentification avec LDAP.
 *
 * @author mabs
 */
class mbsLDAP {
	/**
	 * oxLDAP constructor.
	 */
	protected $params;
	protected $instance;
	protected $ldapInstance;

	public function __construct() {
			
		if(!$this->ldapInstance) {

			$ldap_connections = sfYamlConfigHandler::parseYaml(sfConfig::get("sf_config_dir")."/ldap_config.yml");

			$this->params['base_dn']		= 	$ldap_connections['ldap']['base_dn'];
			$this->params['account_suffix'] = 	$ldap_connections['ldap']['account_suffix'];
			$this->params['use_ssl'] 		= 	$ldap_connections['ldap']['use_ssl'];
			$this->params['user_cn']        =	$ldap_connections['ldap']['user']['base_dn'];
			$this->params['group_cn']       =	$ldap_connections['ldap']['group']['base_dn'];
			try {
				$this->ldapInstance = new adLDAP($this->params);
			}
			catch (adLDAPException $e) {
				echo $e;
				exit();
			}
		}
	}

	public static function getInstance(){
		return new mbsLDAP();
	}
	public function __destruct()
	{
		//$this->ldapInstance->close();
	}

	public function authenticate($username, $password, $user = true){
		if ($user){
			$login = "uid=".$username.",".$this->params['user_cn'];
		} else {
			$login = "uid=".$username.",".$this->params['group_cn'];
		}

		return $this->ldapInstance->authenticate($login, $password);
	}

}