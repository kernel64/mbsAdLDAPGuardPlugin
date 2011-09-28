<?php
/**
 * Authentification avec LDAP.
 *
 * @author mabs
 */
class mbsGuardUserLDAP  {
	
	public static function checkLDAPPassword($username, $password)
	{
		return mbsLDAP::getInstance()->authenticate($username, $password);
	}
	
}