<?php


$_config = array();

// ------------------  CONFIG DB  ------------------- //
$_config['db']['1']['dbhost'] = 'localhost:3300';
$_config['db']['1']['dbuser'] = 'root';
$_config['db']['1']['dbpw'] = 123456;
$_config['db']['1']['dbcharset'] = 'gbk';
$_config['db']['1']['pconnect'] = '0';
$_config['db']['1']['dbname'] = 'bbs';
$_config['db']['1']['tablepre'] = 'pre_';

// ----------------  CONFIG MEMORY  ----------------- //
$_config['memory']['prefix'] = '2yus8Y_';
$_config['memory']['eaccelerator'] = 1;
$_config['memory']['xcache'] = 1;
$_config['memory']['memcache']['server'] = '';
$_config['memory']['memcache']['port'] = 11211;
$_config['memory']['memcache']['pconnect'] = 1;
$_config['memory']['memcache']['timeout'] = 1;

// -----------------  CONFIG CACHE  ----------------- //
$_config['cache']['main']['type'] = '';
$_config['cache']['main']['file']['path'] = 'data/ultraxcache';
$_config['cache']['type'] = 'sql';

// ----------------  CONFIG OUTPUT  ----------------- //
$_config['output']['charset'] = 'gbk';
$_config['output']['forceheader'] = 1;
$_config['output']['gzip'] = '0';
$_config['output']['tplrefresh'] = 1;
$_config['output']['language'] = 'zh_cn';
$_config['output']['staticurl'] = 'static/';

// ----------------  CONFIG COOKIE  ----------------- //
$_config['cookie']['cookiepre'] = 'SXGf_';
$_config['cookie']['cookiedomain'] = '';
$_config['cookie']['cookiepath'] = '/';

// ------------------  CONFIG APP  ------------------ //
$_config['app']['default'] = 'portal';
$_config['app']['domain']['forum'] = '';
$_config['app']['domain']['group'] = '';
$_config['app']['domain']['home'] = '';
$_config['app']['domain']['portal'] = '';
$_config['app']['domain']['mobile'] = '';
$_config['app']['domain']['default'] = '';

// ---------------  CONFIG SECURITY  ---------------- //
$_config['security']['authkey'] = 'ce76c1bZJIaBneP4';
$_config['security']['urlxssdefend'] = 1;
$_config['security']['attackevasive'] = '0';

// ----------------  CONFIG ADMINCP  ---------------- //
$_config['admincp']['founder'] = 1;
$_config['admincp']['forcesecques'] = '0';
$_config['admincp']['checkip'] = 1;
$_config['admincp']['runquery'] = 1;
$_config['admincp']['dbimport'] = 1;

// -----------------  CONFIG HOME  ------------------ //
$_config['home']['allowdomain'] = '0';
$_config['home']['domainroot'] = '';
$_config['home']['holddomain'] = 'www,space,home,forum,portal';


// -------------------  THE END  -------------------- //

?>