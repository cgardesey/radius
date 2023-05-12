<?php

	


	class Axis
	{

		
		function Axis($min, $max)
		{
			$this->min = $min;
			$this->max = $max;

			$this->guide = 10;
		}



		function quantizeTics()
		{
			// Approximate number of decades, in [1..10[

			$norm = $this->delta / $this->magnitude;

			// Approximate number of tics per decade

			$posns = $this->guide / $norm;

		    	if ($posns > 20)
				$tics = 0.05;		// e.g. 0, .05, .10, ...
			else
			if ($posns > 10)
				$tics = 0.2;		// e.g.  0, .1, .2, ...
			else
			if ($posns > 5)
				$tics = 0.4;		// e.g.  0, 0.2, 0.4, ...
			else
			if ($posns > 3)
				$tics = 0.5;		// e.g. 0, 0.5, 1, ...
			else
			if ($posns > 2)
				$tics = 1;		// e.g. 0, 1, 2, ...
			else
			if ($posns > 0.25)
				$tics = 2;		// e.g. 0, 2, 4, 6 
			else
				$tics = ceil($norm);
			
			$this->tics = $tics * $this->magnitude;
		}



		function computeBoundaries()
		{
			// Range

			$this->delta = abs($this->max - $this->min);

			// Check for null distribution
			
			if($this->delta == 0)
				$this->delta = 1;
			
			// Order of magnitude of range

			$this->magnitude = pow(10, floor(log10($this->delta)));
			
			$this->quantizeTics();

			$this->displayMin = floor($this->min / $this->tics) * $this->tics;
			$this->displayMax = ceil($this->max / $this->tics) * $this->tics;
			$this->displayDelta = $this->displayMax - $this->displayMin;
		
			// Check for null distribution
			
			if($this->displayDelta == 0)
				$this->displayDelta = 1;
		}


		
		function setBoundaries($sampleCount, $yMinValue, $yMaxValue)
		{
			$this->sampleCount = $sampleCount;
			$this->yMinValue = $yMinValue;
			$this->yMaxValue = $yMaxValue;
		}



		function getLowerBoundary()
		{
			return $this->displayMin;
		}



		function getUpperBoundary()
		{
			return $this->displayMax;
		}



		function getTics()
		{
			return $this->tics;
		}
	}
?>
