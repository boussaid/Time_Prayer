<?php

class Salat_EventListener_LoadClass
{
	public static function DataWriter($class, array &$extend)
	{
		$extend[] = "Salat_DataWriter_User";
	}

	public static function ControllerSalat($class, array &$extend)
	{
		$extend[] = "Salat_ControllerPublic_Salat";
	}
}