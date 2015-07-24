<?php
	// 数独ファイルを読み込んで16x16の2次元配列に格納する
	$file_path = $argv[1];
	if(!file_exists($file_path))
	{
		echo('file is not exists'.PHP_EOL);
		exit;
	}
	
	$fp = fopen($file_path, "r");
	if($fp == FALSE)
	{
		echo('file can not open'.PHP_EOL);
		exit;
	}
	$y_element_count = 0;
	$element_data = array();
	while(($data = fgetcsv($fp, 0, ",")) !== FALSE)
	{
		if(count($data) != 16)
		{
			echo('x element count is not 16'.PHP_EOL);
			fclose($fp);
			exit;
		}
		$element_data[$y_element_count++] = $data;
	}
	if(count($element_data) != 16)
	{
		echo('y element count is not 16'.PHP_EOL);
		fclose($fp);
		exit;
	}
	fclose($fp);

	// ★★★確認用★★★
	// ★★★ココカラ★★★
	echo "xxxxxxxxxxxxxxxxxxx".PHP_EOL;
	echo "x   sudoku data   x".PHP_EOL;
	echo "xxxxxxxxxxxxxxxxxxx".PHP_EOL;
	for($y = 0; $y < 16; $y++)
	{
		for($x = 0; $x < 16; $x++)
		{
			$element = $element_data[$y][$x];
			if($element === '')
			{
				echo "_";
			}
			else
			{
				echo $element;
			}
			if((($x+1)%4) == 0)
			{
				echo " ";
			}
		}
		echo(PHP_EOL);
		if((($y+1)%4) == 0)
		{
			echo(PHP_EOL);
		}
	}
	// ★★★ココマデ★★★
	
	// ３次元の候補配列に候補となり得る値を入れていく
	$candidate_elements = array();
	for($y = 0; $y < 16; $y++)
	{
		for($x = 0; $x < 16; $x++)
		{
			
		}
	}
?>
