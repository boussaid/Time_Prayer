<?php

class Salat_DataWriter_User extends XFCP_Salat_DataWriter_User
{
	protected function _getFields()
	{
		$fields                            = parent::_getFields();
		$fields['xf_user_option']['salat'] = array('type' => self::TYPE_SERIALIZED, 'default' => '');
		return $fields;
	}
}