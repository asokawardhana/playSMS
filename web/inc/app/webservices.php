<?php
defined('_SECURE_') or die('Forbidden');

// parameters
$h	= trim($_REQUEST['h']);
$u	= trim($_REQUEST['u']);
$p	= trim($_REQUEST['p']);

// type of action (ta) or operation (op), ta = op
$ta	= trim(strtoupper($_REQUEST['ta']));
$op	= trim(strtoupper($_REQUEST['op']));

// send SMS specifics
$to	= trim(strtoupper($_REQUEST['to']));
$msg	= trim($_REQUEST['msg']);
$type	= ( trim($_REQUEST['type']) ? trim($_REQUEST['type']) : 'text' );
$unicode= ( trim($_REQUEST['unicode']) ? trim($_REQUEST['unicode']) : 0 );

// DS specifics
$queue	= trim($_REQUEST['queue']);
$src	= trim($_REQUEST['src']);
$dst	= trim($_REQUEST['dst']);
$dt	= trim($_REQUEST['dt']);
$slid	= trim($_REQUEST['slid']);
$c	= trim($_REQUEST['c']);
$last	= trim($_REQUEST['last']);

// default error return
$ret = "ERR 102";

if ($op) { $ta = $op; };
if ($ta) {
	switch ($ta) {
		case "PV":
			if ($c_uid = validatetoken($h)) {
				$u = uid2username($c_uid);
				$ret = webservices_pv($u,$to,$msg,$type,$unicode);
			} else {
				$ret = "ERR 100";
			}
			break;
		case "BC":
			if ($c_uid = validatetoken($h)) {
				$u = uid2username($c_uid);
				$ret = webservices_bc($u,$to,$msg,$type,$unicode);
			} else {
				$ret = "ERR 100";
			}
			break;
		case "DS":
			if ($c_uid = validatetoken($h)) {
				$u = uid2username($c_uid);
				$ret = webservices_ds($u,$queue,$src,$dst,$dt,$slid,$c,$last);
			} else {
				$ret = "ERR 100";
			}
			break;
		case "CR":
			if ($c_uid = validatetoken($h)) {
				$u = uid2username($c_uid);
				$ret = webservices_cr($u);
			} else {
				$ret = "ERR 100";
			}
			break;
		case "GET_TOKEN":
			if (validatelogin($u,$p)) {
				if ($token = user_getfieldbyusername($u,'token')) {
					$ret = "OK ".$token;
				} else {
					$ret = "ERR 104";
				}
			} else {
				$ret = "ERR 100";
			}
			break;
		default:
			// output do not require valid login
			$ret = webservices_output($ta,$_REQUEST);
	}
}

echo $ret;

?>