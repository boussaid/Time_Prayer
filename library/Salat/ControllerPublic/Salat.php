<?php

class Salat_ControllerPublic_Salat extends XFCP_Salat_ControllerPublic_Salat
{
	public function actionSalat()
	{
		$visitor = XenForo_Visitor::getInstance();
		if (!$visitor->hasPermission('salat', 'salat_edit_user'))
		{
			return $this->responseNoPermission();
		}
		$salatFilde = ($visitor->salat) ? @unserialize($visitor->salat) : array();
		$viewParams = array(
			'timeZones' => XenForo_Helper_TimeZone::getTimeZones(),
			'salat'		=> $salatFilde
		);
		//return $this->responseView('Salat_ViewPublic_User', 'salat_card', $viewParams);
		return $this->_getWrapper(
			'account', 'salat',
			$this->responseView(
				'Salat_ViewPublic_User',
				'salat_card',
				$viewParams
			)
		);
	}

	public function actionSalatSave()
	{
		$this->_assertPostOnly();
		$salatFields = $this->_input->filter(array(
			'salat' => XenForo_Input::ARRAY_SIMPLE,
			'timezone' => XenForo_Input::STRING,
		));
		$visitor = XenForo_Visitor::getInstance();

		if (!$visitor->hasPermission('salat', 'salat_edit_user'))
		{
			return $this->responseNoPermission();
		}

		$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
		$writer->setExistingData(XenForo_Visitor::getUserId());
		$writer->bulkSet($salatFields);
		$writer->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			$this->getDynamicRedirect(XenForo_Link::buildPublicLink('account/salat'))
		);
	}
}