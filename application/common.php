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
'Web'=>[
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
function getCategory(){
	$category=[
		'Web'=>[
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
	return $category;
}
function uploadimg($file){
    #移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH.'public'.DS.'static'.DS.'index'.DS.'upload.');
        if($info){
            // 成功上传后 获取上传信息
            $res['extension']= $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            $res['savename']=$info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            $res['filename']=$info->getFilename(); 
            $res['isuploaded']=true;
        }else{
        	$res['isuploaded']=false;
            // 上传失败获取错误信息
            $res['errormsg']=$file->getError();
        }
        return $res;
    }
    $res['isuploaded']=false;
    // 上传失败获取错误信息
    $res['errormsg']='请选择合适的图片';
    return $res;
}