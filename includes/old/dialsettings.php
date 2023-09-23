<?php

	$angle = -.2;                      // The span of the gauge arc
	$lineWidth = 0.1;                  // The line thickness
	$radiusScale = .9;                  // Relative radius

	$pointerlength = 0.6;              // Relative to gauge radius
	$pointerstrokeWidth = 0.035;       // The thickness
	$pointercolor = '#000000';         // Fill color

	$enableStaticLabels = false;
	if ($enableStaticLabels) {$staticLabels="";} else {$staticLabels="//";}
	$staticLabelsfont = '0.6em arial'; // Specifies font
	$staticLabelscolor = '#333';       // Optional = Label text color
	$staticLabelsfractionDigits = 0;   // Optional = Numerical precision. 0=round off.

	$Ticksdivisions = 5;
	$TicksdivWidth = 1.1;
	$TicksdivLength = 0.7;
	$TicksdivColor = '#333333';
	$TickssubDivisions = 3;
	$TickssubLength = 0.5;
	$TickssubWidth = 0.6;
	$TickssubColor = '#666666';

?>