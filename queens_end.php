<?php
$start = microtime(true);

define('BOARD_SIZE', 8);
for ($row = 0; $row < BOARD_SIZE; $row++) {
    $layout[$row] = 1 << $row;

}
function showBinary($val) {
    return str_pad(decbin($val), 8, '0', STR_PAD_LEFT) . '<br>';
}


function checkDiagonals($layout) {
	/* var_dump (  $layout ); */
    $row = 0;
	
    while ($row < BOARD_SIZE) {
        // Initialize offset for row and column numbers
        $offset = 1;
        // Use the offset to check each row in turn against the current row
        while ($offset < BOARD_SIZE - $row) {
			$ld = $layout[$row + $offset] << $offset;
            $rd = $layout[$row + $offset] >> $offset;

            if ($layout[$row] == $ld)
			{
		return false;
            }
			

			if ($layout[$row] ==$layout[$row + $offset] )  
			{
			return false;
			}
			
			
			if (  $layout[$row] ==$rd         )
			{
				
				return false;
				
				
			}
				
			
            $offset++;
        }
        $row++;
    }
	
	
    // If no attacks have been detected, return true
    return true;
}



function randomLayout($layout) {
	$digits =array(0,1,2,3,4,5,6,7);
	$digits1 = array(1,2,4,8,16,32,64,128);
	
	while (!empty($digits1))
	{
		$x = rand(0,count($digits)-1);
		

		if (   !empty($digits1[$x]) )
		{
			
	 $layout[count($digits1)-1] = $digits1[$x];
	 
	 
	 unset($digits1[$x]);
		}
	}
    return $layout;
}





// Function to rotate a board 90 degrees
function rotateBoard($layout) {
    $row = 0;
    while ($row < BOARD_SIZE) {
        // Convert the number to binary, and get its length
        $offset = strlen(decbin($layout[$row])) - 1;
        // Add a queen to each column starting from the left
        $rotated[$offset] = 1 << (BOARD_SIZE - $row - 1);
        $row++;
    }
    // Sort the new array using the array keys in ascending order
    ksort($rotated);
    return $rotated;
}

function findRotations($layout, &$solutions) {
    $rotation = $layout;
    // Rotate the board through 90, 180 & 270 degrees
    for ($i = 0; $i < 3; $i++) {
        $rotation = rotateBoard($rotation);
        if (!in_array($rotation, $solutions)) {
            $solutions[] = $rotation;
			/* echo "<p>Total solutions (including rotations and reflectionsxxxxxx11): " . count($solutions); */
        }
    }

    // Reflected
    $reflected = array_reverse($layout);
    if (!in_array($reflected, $solutions)) {
		/* echo "<p>Total solutions (including rotations and reflectionsxxxxxx22): " . count($solutions); */
        $solutions[] = $reflected;
    }

    // Rotate the reflected version through 90, 180 & 270 degrees
    $rotation = $reflected;
    for ($i = 0; $i < 3; $i++) {
        $rotation = rotateBoard($rotation);
        if (!in_array($rotation, $solutions)) {
            $solutions[] = $rotation;
		/* 	echo "<p>Total solutions (including rotations and reflectionsxxxxxx33): " . count($solutions); */
        }
    }
}


function renderBoard($layout) {

if ( empty($_SESSION['index']))
{
	
	$_SESSION['index']=0;
	
}

$_SESSION['index']++;
    echo '<table>';
	echo '<h1>'.$_SESSION['index'].'</h1>';
    for ($row = 0; $row < BOARD_SIZE; $row++) {
        echo '<tr>';
        for ($col = 0; $col < BOARD_SIZE; $col++){
            if ($layout[$row] == 1 << $col) {
                echo '<td><img src="crown.png" width="28" height="26"></td>';
            } else {
                echo '<td>&nbsp;</td>';
            }
        }
        echo '</tr>';
    }
    echo '</table>';
	
}
?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Eight Queens</title>
        <link href="queens.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <h1>Solving the Eight Queens Problem</h1>
<?php
$size = BOARD_SIZE - 1;
$solutions = array();
$unique = 0;
$i = 0;
do {
	

    if (checkDiagonals($layout)) {
        if (!in_array($layout, $solutions)) {
            $solutions[] = $layout;
            findRotations($layout, $solutions);
            renderBoard($layout);
           $unique++;
			if($unique==12 )
			{
				
				break;
			}
			
        }
		$i++;
    }
} while ( $layout=randomLayout($layout)  );
	
echo "<p>Total solutions (including rotations and reflections): " . count
    ($solutions)
    . "<br>";
echo $unique . ' unique solutions found <br>';


$end = microtime(true);
echo 'Time taken: ' . ($end - $start) . ' seconds</p>';



?>
    </body>
</html>