<?php

class PHPengine
{
    protected $log;
    protected $dir;
    protected $csvparam=array();
    protected $id;
    protected $start;
    protected $end;
    protected $outinterval;

    public function __construct($settings)
    {
        if (isset($settings['datadir'])) $this->dir = ROOT.$settings['datadir'].DS;
        $this->log = new EmonLogger(__FILE__);
    }

    public function get_data_exact($name,$start,$end,$outinterval)
    {
        $name = (int) $name;
        $start = floatval($start)/1000;
        $end = floatval($end)/1000;
        $outinterval= (int) $outinterval;
        if ($outinterval<1) $outinterval = 1;
        if ($end<=$start) return false;

        $numdp = (($end - $start) / $outinterval);
        if ($numdp>5000) return false;
        if ($outinterval<5) $outinterval = 5;

        // If meta data file does not exist then exit
        if (!$meta = $this->get_meta($name)) return false;
        // $meta->npoints = $this->get_npoints($name);

        $data = array();
        $time = 0; $i = 0;

        // The datapoints are selected within a loop that runs until we reach a
        // datapoint that is beyond the end of our query range
        $fh = fopen($this->dir.$name."_0.dat", 'rb');
        while($time<=$end)
        {
            $time = $start + ($outinterval * $i);
            $pos = round(($time - $meta->start_time) / $meta->interval[0]);

            $value = null;

            if ($pos>=0 && $pos < $meta->npoints[0])
            {
                // read from the file
                fseek($fh,$pos*4);
                $val = unpack("f",fread($fh,4));
                // add to the data array if its not a nan value
                if (!is_nan($val[1])) {
                    $value = $val[1];
                } else {
                    $value = null;
                }
            }
            $data[] = array($time*1000,$value);

            $i++;
        }
        return $data;
    }


    /**
     * Get the last value from a feed
     *
     * @param integer $feedid The id of the feed
    */
    public function lastvalue($id)
    {
        $id = (int) $id;

        // If meta data file does not exist then exit
        if (!$meta = $this->get_meta($id)) return false;
        if ($meta->npoints[0]>0)
        {
            $fh = fopen($this->dir.$meta->id."_0.dat", 'rb');
            $size = $meta->npoints[0]*4;
            fseek($fh,$size-4);
            $d = fread($fh,4);
            fclose($fh);

            $val = unpack("f",$d);
            $time = date("Y-n-j H:i:s", $meta->start_time + $meta->interval[0] * $meta->npoints[0]);

            return array('time'=>$time, 'value'=>$val[1]);
        }
        else
        {
            return array('time'=>0, 'value'=>0);
        }
    }
    public function generalexport($id,$start,$layer='')
    {
        $id = (int) $id;
        $start = (int) $start;
        $layer = (int) $layer;

        $feedname = $id."_$layer.dat";

        // If meta data file does not exist then exit
        if (!$meta = $this->get_meta($id)) {
            $this->log->warn("PHPFiwa:post failed to fetch meta id=$id");
            return false;
        }

        // There is no need for the browser to cache the output
        header("Cache-Control: no-cache, no-store, must-revalidate");

        // Tell the browser to handle output as a csv file to be downloaded
        header('Content-Description: File Transfer');
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$feedname}");

        header("Expires: 0");
        header("Pragma: no-cache");

        // Write to output stream
        $fh = @fopen( 'php://output', 'w' );

        $primary = fopen($this->dir.$feedname, 'rb');
        $primarysize = filesize($this->dir.$feedname);

        $localsize = $start;
        $localsize = intval($localsize / 4) * 4;
        if ($localsize<0) $localsize = 0;

        // Get the first point which will be updated rather than appended
        if ($localsize>=4) $localsize = $localsize - 4;

        fseek($primary,$localsize);
        $left_to_read = $primarysize - $localsize;
        if ($left_to_read>0){
            do
            {
                if ($left_to_read>8192) $readsize = 8192; else $readsize = $left_to_read;
                $left_to_read -= $readsize;

                $data = fread($primary,$readsize);
                fwrite($fh,$data);
            }
            while ($left_to_read>0);
        }
        fclose($primary);
        fclose($fh);
        exit;
    }

    public function csvstart($id,$start,$end,$outinterval){
                //global $param;
                global $csv_parameters;
        $this->csvparam['cs']=$csv_parameters['csv_field_separator'];
        $this->csvparam['ds']=$csv_parameters['csv_decimal_place_separator'];
        $this->csvparam['ts']=$csv_parameters['csv_thousandsepar_separator'];
        $this->csvparam['df']=$csv_parameters['csv_dateformat'];
        $this->csvparam['tf']=$csv_parameters['csv_timeformat'];
        if (isset($_SESSION['csv_field_separator']))$this->csvparam['cs'] = $_SESSION['csv_field_separator'];
        if (isset($_SESSION['csv_decimal_place_separator']))$this->csvparam['ds'] = $_SESSION['csv_decimal_place_separator'];
        if (isset($_SESSION['csv_thousandsepar_separator'])) $this->csvparam['ts'] = $_SESSION['csv_thousandsepar_separator'];
        if (isset($_SESSION['csvdate'])) $this->csvparam['df'] = $_SESSION['csvdate'];
        if (isset($_SESSION['csvtime'])) $this->csvparam['tf'] = $_SESSION['csvtime'];

        $this->id = intval($id);
        $this->start = intval($start);
        $this->end = intval($end);
        $this->outinterval= intval($outinterval);

    }
}

