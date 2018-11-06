<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getCategoryNames($id){
$category=[
'imooc'=>[
1=>'站务与公告',
2=>'反馈',
3=>'使用指南'
],
'Technology'=>[
4=>'Geek Talks',
5=>'程序猿',
6=>'分享与创造'
],
'Mobile'=>[
7=>'iPhone',
8=>'Android'
],
'Lifestyle'=>[
9=>'Discovery',
10=>'意欲蔓延'
]
];
foreach ($category as $key => $value) {
	foreach ($value as $k => $v) {
		if($k===$id){
			return [$key,$v];
		}
	}
}
}