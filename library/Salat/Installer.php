<?php
class Salat_Installer
{
    public static function install($previous)
    {
        if (XenForo_Application::$versionId < 1020070) {
            throw new XenForo_Exception('هذا المنتج يتطلب إصدار 1.2.0 بيتا 1 و أعلى.', TRUE);
        }
        $db = XenForo_Application::getDb();
        if (!$previous || $previous < 4012015) {
            try {
                $db->query("ALTER TABLE xf_user_option ADD salat MEDIUMBLOB NOT NULL");               
            } 
            catch (Zend_Db_Exception $e) {}
        }
    }

    public static function uninstall()
    {
        try {
            $db = XenForo_Application::getDb();
            $db->query("ALTER TABLE xf_user_option DROP salat");
            } 
            catch (Zend_Db_Exception $e) {}
    }
}