<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) Vendor
 * 
 * @defgroup    Test Test module
 * @ingroup     VendorModules
 *
 * @{
 */

include BX_DIRECTORY_PATH_MODULES . '/templates/QueryBuilder.php';


bx_import('BxDolModule');

class VndTestModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    function serviceCustom()
    {
        global $html_data_accounts;
        global $sAcountId;
        global $created_result;
        global $test;
        $servername = BX_DATABASE_HOST;
        $username = BX_DATABASE_USER;
        $password = BX_DATABASE_PASS;
        $dbname = BX_DATABASE_NAME;

        $mysql = new QueryBuilder($servername, $username, $password, $dbname);
        $created_result = $mysql->createCustomProfileTable();
        $html_data_accounts = $mysql->accounts();
        $sAcountId = BxDolAccount::getInstance()->id();
        ob_start();
        include BX_DIRECTORY_PATH_MODULES . "/templates/file.php";
        $string = ob_get_clean();
        return $string;
    }
    function serviceInvite()
    {
        ob_start();
        include BX_DIRECTORY_PATH_MODULES . "/templates/password.php";
        $string = ob_get_clean();
        return $string;
    }
}

/** @} */
