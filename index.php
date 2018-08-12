<?php

class pronounceMoney{
  

    public $number;

    private $splitMoney; 

    private $euro;
    private $cent; 

    private $hundred;
    private $thousand;

    private $numbers = ["0" => "","1" => "one", "2" => "two", "3" => "three", "4" => "four", "5" => "five", "6" => "six", "7" => "seven", "8" => "eight", "9" => "nine", "10" => "ten", "11" => "eleven", "12" => "twelve", "13" => "thirteen", "14" => "fourteen", "15" => "fifteen", "16" => "sixteen", "17" => "seventeen","18" => "eighteen", "19" => "nineteen"];
    private $decades = ["0" => "","2" => "twenty","3" => "thirty","4" => "forty", "5" => "fifty", "6" => "sixty", "7" => "seventy", "8" => "eighty", "9" => "ninety"];
    private $thousands = ["hundred","thousand"];


    function file($input,$output){

        $inputFile = fopen($input,"r");
        $outputFile = fopen($output,"w");

        while(!feof($inputFile)){
            fwrite($outputFile, $this->convert(fgets($inputFile))); ;
        }
        
        fclose($outputFile);
        fclose($inputFile);

        echo "It's Done!\n";

    }

    function convert($number){


        $splitMoney = $this->splitMoney(trim($number));

        if($splitMoney){

            $thousands = $this->calculateHundreds($this->euro["thousand"]);
            $hundreds = $this->calculateHundreds($this->euro["hundred"]);

            if($hundreds == "zero" AND $thousands == "zero"){

                return "zero euro ".$this->cent."\n";

            }elseif($thousands == "zero" AND $hundreds != "zero"){

                return $this->calculateHundreds($this->euro["hundred"])." Euro ".$this->cent."\n";

            }elseif($thousands != "zero" AND $hundreds == "zero"){

                return $this->calculateHundreds($this->euro["thousand"])." ".$this->thousands[1]." Euro ".$this->cent."\n";

            }else{

                return $this->calculateHundreds($this->euro["thousand"])." ".$this->thousands[1]." ".$this->calculateHundreds($this->euro["hundred"])." Euro ".$this->cent."\n";

            }

        }else{
            return "";
        }
    

    } 

    function splitMoney($number){

        $number = str_replace(".","",$number);
        $number = str_replace(" ","",$number);
        
        $number = explode(",",$number);

        if(!isset($number[1])){
            $number[1] = 0;
        }
    
        if(!is_numeric($number[0]) OR !is_numeric($number[1])){

            return false;
        
        }elseif($number[0] > 999999){
        
            return false;
        
        }else{
        
            $this->euroCalculate($number[0]);
            $this->centCalculate($number[1]);
    
            return true;
        }
        
    }


    
    function calculateHundreds($number){
        $pronounce = "";
        $numberTwoDigit = substr($number,-2);

        if(intval($number) > 99){
            $pronounce = $pronounce . $this->numbers[substr($number,0,1)]." ".$this->thousands[0]." ";
        }
        
        if(intval($numberTwoDigit) > 19){
            $pronounce = $pronounce . $this->decades[substr($numberTwoDigit,0,1)]."-".$this->numbers[substr($numberTwoDigit,-1)];
        }else{
            $pronounce = $pronounce . $this->numbers[intval($numberTwoDigit)];
        }

        if($pronounce == ""){
            $pronounce = "zero";
        }

        return $pronounce;
        
    }

    function centCalculate($splitMoney){
        
        $cent;

        if(!isset($splitMoney)){
            $cent = 0;
        }else{
            $cent = $splitMoney;
        }

        if($cent > 99){

            $cent =  substr($cent,0,2)." Cent";

        }elseif($cent[0] == 0 and isset($cent[1])){

            $cent = $cent[1]." Cent"; 

        }elseif($cent < 10){

                $cent = $cent."0 Cent";

        }if($cent == 0){

            $cent = "Zero Cent";

        }else{
            $cent = $cent." Cent";
        }

        $this->cent = $cent;

        return true;

    }

    function euroCalculate($splitMoney){

        $splitMoney = str_replace(".","",$splitMoney);
        $splitMoney = str_replace(" ","",$splitMoney);

        $this->euro["hundred"] = substr($splitMoney,-3);
        $this->euro["thousand"] =  substr($splitMoney,-6,-3);

        return true;

    }

}


$pronounceMoney = new pronounceMoney();

echo $pronounceMoney->convert("0");

//$pronounceMoney->file("Input.txt","Output.txt");



