<?php
/**
 * Class CSVReportUtility
 * This class is used to create CSV files
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class CSVReportUtility {
    
    var $headers;
    var $fileName;
    var $data;
    var $fileType;

    function __construct($headers = null, $data = null, $fileName = null, $fileType = null) {

        if ($headers ==null) {
            $this->setHeader(array());
        } else {
            $this->setHeader($headers);
        }
        if ($fileName ==null) {
            $this->setFileName(date("Y-m-d_H:i:s") .".csv");
        } else {
            $this->setFileName($fileName);
        }
        
        if ($data ==null) {
            $this->setData(array());
        } else {
            $this->setData($data);
        }
        
        if ($fileType ==null) {
            $this->setFileType("text/csv");
        } else {
            $this->setFileType($fileType);
        }
    }
    
     public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    public function getFileName() {
        return $this->fileName;
    }
    public function setHeader($headers) {
        $this->headers = $headers;
    }

    public function getHeader() {
        
        $this->headers   =  '"' . implode('","',$this->headers) . '"';
        return $this->headers;

    }

    public function setFileType($fileType) {
        $this->fileType =  $fileType;
    }

    public function getFileType() {
        return $this->fileType;
    }
    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function getCsvDataString() {

       
        $csvData = "";

        foreach ($this->data as $row) {
            foreach($row as $key=>&$value) {
                $value = str_replace('"', '""', $value); // handle quotes inside fields
            }
            $csvData  =  $csvData . '"' . implode('","',$row) . '"'. "\n";
        }

        return $csvData;
    }
    public function exportCSVData(){
		
		$csvContents = $this->getHeader() . "\n" . $this->getCsvDataString();
		$this->executeHeaders();
        echo $csvContents;
        die;
	}

    public function executeHeaders() {

        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: " . $this->getFileType());
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $this->getFileName() . '";');
        header("Content-Transfer-Encoding: none");
    }
    
}