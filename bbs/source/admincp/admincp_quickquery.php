<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_quickquery.php 9284 2010-04-28 01:52:59Z monkey $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$simplequeries = array(
	array('comment' => '���ٿ�����̳��鹦��', 'sql' => ''),
	array('comment' => '���� ���а�� �������վ', 'sql' => 'UPDATE {tablepre}forum_forum SET recyclebin=\'1\''),
	array('comment' => '���� ���а�� Discuz! ���롱', 'sql' => 'UPDATE {tablepre}forum_forum SET allowbbcode=\'1\''),
	array('comment' => '���� ���а�� [IMG] ���롱', 'sql' => 'UPDATE {tablepre}forum_forum SET allowimgcode=\'1\''),
	array('comment' => '���� ���а�� Smilies ����', 'sql' => 'UPDATE {tablepre}forum_forum SET allowsmilies=\'1\''),
	array('comment' => '���� ���а�� ���ݸ�����', 'sql' => 'UPDATE {tablepre}forum_forum SET jammer=\'1\''),
	array('comment' => '���� ���а�� ��������������', 'sql' => 'UPDATE {tablepre}forum_forum SET allowanonymous=\'1\''),

	array('comment' => '���ٹر���̳��鹦��', 'sql' => ''),
	array('comment' => '�ر� ���а�� �������վ', 'sql' => 'UPDATE {tablepre}forum_forum SET recyclebin=\'0\''),
	array('comment' => '�ر� ���а�� HTML ����', 'sql' => 'UPDATE {tablepre}forum_forum SET allowhtml=\'0\''),
	array('comment' => '�ر� ���а�� Discuz! ����', 'sql' => 'UPDATE {tablepre}forum_forum SET allowbbcode=\'0\''),
	array('comment' => '�ر� ���а�� [IMG] ����', 'sql' => 'UPDATE {tablepre}forum_forum SET allowimgcode=\'0\''),
	array('comment' => '�ر� ���а�� Smilies ����', 'sql' => 'UPDATE {tablepre}forum_forum SET allowsmilies=\'0\''),
	array('comment' => '�ر� ���а�� ���ݸ�����', 'sql' => 'UPDATE {tablepre}forum_forum SET jammer=\'0\''),
	array('comment' => '�ر� ���а�� ������������', 'sql' => 'UPDATE {tablepre}forum_forum SET allowanonymous=\'0\''),

	array('comment' => '��Ա�������', 'sql' => ''),
	array('comment' => '��� ���л�Ա ���ֽ��׼�¼', 'sql' => 'TRUNCATE {tablepre}common_credit_log;'),
);

?>