<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'encoding.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'db/conn.php');
	require_once($_SERVER['DOCUMENT_ROOT']."mail/sendmail.php");
	require_once($_SERVER['DOCUMENT_ROOT'].'db/jsonResponse.php');
	session_start();

	
	$sqlAttr = array();
	
	$sqlAttr['path']=$_POST['path'];
	$sqlAttr['filePath']=$_POST['filePath'];
	$sqlAttr['type']=$_POST['type'];
	
	require_once('ftp_class.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'include/function_debug.php');

	$config = [			//连线server
		'host'=>'1**.***.*.**1',		//ip
		'user'=>'*****',					//用户名
		'pass'=>'s*******9'			//密码
	];
	$ftp = new Ftp($config);
	$result = $ftp->connect();
	if (!$result)
	{
		echo $ftp->get_error_msg();
	}

	
	if($sqlAttr['type'] == 'upLoad')		//上传文件到ftp
	{
		$local_file = $sqlAttr['path'];				//本地文件路径
		$remote_file = $sqlAttr['filePath'];		//远程文件路径
		
		if ($ftp->upload($local_file,$remote_file))
		{
			echo "上传成功";
		}
		else
		{
			echo "上传失败";
		}
	}
	else if($sqlAttr['type'] == 'deleteDoc') 	//删除ftp服务器端的文件
	{
		$remote_file = "";			//远程文件路径
		
		if ($ftp->delete_file($remote_file))
		{
			echo "删除成功";
		}
		else
		{
			echo "删除失败";
		}
	}
	else if($sqlAttr['type'] == 'deleteFile')		//删除ftp上的目录(目录必须是空目录)
	{
		$remote_path='';		//远程要删除的目录路径
		
		if ($ftp->delete_dir($remote_path))
		{
			echo "目录删除成功";
		}
		else
		{
			echo "目录删除失败";
		}
	}
	else if($sqlAttr['type'] == 'downLoad')		//下载ftp上的文件到本地
	{
		$local_file2 = '';			//本地要存放文件的路径
		$remote_file2='';			//远程要下载文件的路径
		if ($ftp->download($local_file2,$remote_file2))
		{
			echo "下载成功";
		}
		else
		{
			echo "下载失败";
		}
	}
	else if($sqlAttr['type'] == 'move' || $sqlAttr['type'] == 'rename')		//移动ftp上的文件 or 重新命名ftp上的文件
	{
		$local_file3 = '';			//原文件存放的路径   or  要被重新命名的文件路径
		$remote_file3='';			//文件要存放的新路径 or  文件的路径和新名字
		if ($ftp->remane($local_file3,$remote_file3))
		{
			echo "移动成功";
		}
		else
		{
			echo "移动失败";
		}
	}
	
	
	$ftp->close();


	function p($data='')
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
?>