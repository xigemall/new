<?php
//test.php
//
// Usage on command-line: php test.php <file|textstring>
// Usage on web: 
error_reporting(E_ALL);

//����������?
$text = <<<EOF
�й������ԱӦ����������̫�������Ա����
��չ�й���
�Ϻ���ѧ�����
����Ķ���
����������һ����ģ��������������ȥ�ϰ�
��ױ�ͷ�װ
����Ű��ֻ��ˣ�������ÿ�
����������һ���н������������н���������
������ȥ�����ˣ�������ͷ���Ե�
ŷ������������������������������
ë�󶫱���������
���г����� ���г�����Q1,����Ҫ��Q�ҳ�ֵ
EOF;

if (isset($_SERVER['argv'][1])) 
{
	$text = $_SERVER['argv'][1];
	if (strpos($text, "\n") === false && is_file($text)) $text = file_get_contents($text);
}
elseif (isset($_SERVER['QUERY_STRING']))
{
	$text = $_SERVER['QUERY_STRING'];
}

// 
require 'PSCWS4.php';
$cws = new PSCWS4('gbk');
$cws->set_dict('etc/dict.xdb');
$cws->set_rule('etc/rules.ini');
//$cws->set_multi(3);
//$cws->set_ignore(true);
//$cws->set_debug(true);
//$cws->set_duality(true);
$cws->send_text($text);

if (php_sapi_name() != 'cli') header('Content-Type: text/plain');
echo "pscws version: " . $cws->version() . "\n";
echo "Segment result:\n\n";
while ($tmp = $cws->get_result())
{	
	$line = '';
	foreach ($tmp as $w) 
	{
		if ($w['word'] == "\r") continue;
		if ($w['word'] == "\n")		
			$line = rtrim($line, ' ') . "\n";
		//else $line .= $w['word'] . "/{$w['attr']} ";
		else $line .= $w['word'] . " ";
	}
	echo $line;
}

// top:
echo "Top words stats:\n\n";
$ret = array();
$ret = $cws->get_tops(10,'r,v,p');
echo "No.\tWord\t\t\tAttr\tTimes\tRank\n------------------------------------------------------\n";
$i = 1;
foreach ($ret as $tmp)
{
	printf("%02d.\t%-16s\t%s\t%d\t%.2f\n", $i++, $tmp['word'], $tmp['attr'], $tmp['times'], $tmp['weight']);
}
$cws->close();
?>