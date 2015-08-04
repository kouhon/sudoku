<?php
/**
 * 16x16の数独を解くプログラム
 * ルール
 * ・一つの列には0-9A-Fの一つずつが入る。
 * ・一つの行には0-9A-Fの一つずつが入る。
 * ・太枠の4×4にはそれぞれ、0-9A-Fの一つずつが入る。
 * ※ナナメやその他のグループに制約はありません。
 */
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

	print_sudoku($element_data);
	solve_sudoku($element_data);

	function print_sudoku($element_data)
	{
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
	}	

	/**
	 * 数独を解く
	 * @param element_data	数独のデータが入った2次元配列
	 */
	function solve_sudoku($element_data)
	{	
		if(!is_array($element_data))
		{
			return;
		}
		$is_solve_sudoku = true;
		// ３次元の候補配列に候補となり得る値を入れていく
		for($x = 0; $x < 16; $x++)
		{
			for($y = 0; $y < 16; $y++)
			{
//echo "[".$x."][".$y."]".PHP_EOL;
				if($element_data[$y][$x] != '')
				{
					$is_solve_sudoku = false;
					continue;
				}
				$condidate_element = get_candidate_element($x, $y, $element_data);
				if(count($condidate_element) == 1)
				{
echo "[".$x."][".$y."]".PHP_EOL;
					var_dump($condidate_element);
					$element_data[$y][$x] = $condidate_element[0];
					print_sudoku($element_data);
				}
			}
		}
		if(!$is_solve_sudoku)
		{
			solve_sudoku($element_data);
		}
	}

	/**
	 * 指定された位置の候補要素を取得する
	 * @param x		X位置
	 * @param y		Y位置
	 * @param element_data	要素が入った2次元配列
	 * @return 候補要素が入った配列
	 */
	function get_candidate_element($x, $y, $element_data) 
	{
//echo "get_candidate_element".PHP_EOL;
		if(!is_int($x) ||
		   !is_int($y) ||
		   !is_array($element_data))
		{
			return null;
		}
 
		$x_axis_candidate_element = get_candidate_element_from_x_axis($y, $element_data);
		$y_axis_candidate_element = get_candidate_element_from_y_axis($x, $element_data);
		$square_candidate_element = get_candidate_element_from_4x4_square($x, $y, $element_data);
		
		$candidate_element = array();
		$i = 0;
		for($v = 0; $v < 16; $v++)
		{
			if(!in_array(dechex($v), $x_axis_candidate_element))
			{
				continue;	
			}
			if(!in_array(dechex($v), $y_axis_candidate_element))
			{
				continue;
			}
			if(!in_array(dechex($v), $square_candidate_element))
			{
				continue;
			}
			$candidate_element[$i++] = dechex($v);
		}
//var_dump($candidate_element);
		return $candidate_element;		
	}

	/**
	 * 指定されたY位置でのX軸での候補要素を取得する
	 * @param y		Y位置
	 * @param element_data	要素が入った2次元配列
	 * @return 候補要素が入った配列
	 */
	function get_candidate_element_from_x_axis($y, $element_data)
	{
//echo "get_candidate_element_from_x_axis".PHP_EOL;
		if(!is_int($y) || 
		   !is_array($element_data))
		{
			return null;
		}

		$x_axis_element = array();
		for($x = 0; $x < 16; $x++)
		{
			if($element_data[$y][$x] == '')
			{
				continue;
			}
			$x_axis_element[$x] = $element_data[$y][$x];
		}
		$element_array = array();
		for($v = 0; $v < 16; $v++)
		{
			if(in_array(dechex($v), $x_axis_element))
			{
				continue;
			}
			$element_array[$v] = dechex($v);
		}
//var_dump($element_array);
		return $element_array;
	}

	/**
	 * 指定されたX位置でのY軸での候補要素を取得する
	 * @param x		X位置
	 * @param element_data	要素が入った2次元配列
	 * @return 候補要素が入った配列
	 */
	function get_candidate_element_from_y_axis($x, $element_data)
	{
//echo "get_candidate_element_from_y_axis";
		if(!is_int($x) ||
		   !is_array($element_data))
		{
			return null;
		}

		$y_axis_element = array();
		for($y = 0; $y < 16; $y++)
		{
			if($element_data[$y][$x] == '')
			{
				continue;
			}
			$y_axis_element[$y] = $element_data[$y][$x];
		}
		$element_array = array();
		for($v = 0; $v < 16; $v++)
		{
			if(in_array(dechex($v), $y_axis_element))
			{
				continue;
			}
			$element_array[$v] = dechex($v);
		}
//var_dump($element_array);
		return $element_array;
	}

	/**
	 * 指定されたXとY位置で入っている４x４四方の候補要素を取得する
	 * @param x		X位置
	 * @param y		Y位置
	 * @param element_data	要素が入った２次元配列
	 * @return 候補要素が入った配列
	 */
	function get_candidate_element_from_4x4_square($x, $y, $element_data)
	{
//echo "get_candidate_element_from_4x4_square".PHP_EOL;
		if(!is_int($x) ||
		   !is_int($y) ||
		   !is_array($element_data))
		{
			return null;
		} 
		
		$square_upperleft_x = ((int)($x / 4)) * 4;
		$square_upperleft_y = ((int)($y / 4)) * 4;
		$square_element = array();
		$i=0;
		for($x = $square_upperleft_x; $x < $square_upperleft_x + 4; $x++)
		{
			for($y = $square_upperleft_y; $y < $square_upperleft_y + 4; $y++)
			{
				if($element_data[$y][$x] == '')
				{
					continue;
				}
				$square_element[$i++] = $element_data[$y][$x];
			}
		}
		$element_array = array();
		for($v = 0; $v < 16; $v++)
		{
			if(in_array(dechex($v), $square_element))
			{
				continue;
			}
			$element_array[$v] = dechex($v);
		}
//var_dump($element_array);
		return $element_array;	
	}
?>
