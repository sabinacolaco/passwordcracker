<?php
session_start();
set_time_limit(0);
include('config/database.php');

class Main {
    
    const SALT = 'ThisIs-A-Salt123';
    private $db;

    public function __construct()
    {
        if (!isset($this->db)) {
            try {
                $this->db = new mysqli($GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['db_name']);
            } catch (Exception $e) {
                $error = $e->getMessage();
                echo $error;
            }
        }
    }


    /**
    * crackPassword function
    * This is the main function which handles all the types of passwords i.e. Easy, Mediums and Hard, and processes the desired output
    * Called from ajaxprocess.php
    * @return $resultArr: user IDs of the matched passwords
    */
    public function crackPassword()
    {
        $resultArr = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selOption = $this->validateData($_POST['optradio']);
            if (!empty($selOption)) {
                $sql = "SELECT * FROM not_so_smart_users";
                /* If the selected option is Easy, it executes the following piece of code.
                Lists the user IDs who have numbers as their passwords i.e. 12345 */
                if ($selOption === 'Easy') {
                    $result = $this->db->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            /* NOTE: Since the password involves 5 digit numbers, it loops through 10000 to 99999,
                            to get the passwords in that range. Hash the number and match with the hashed password stored in the database*/
                            
                            for ( $i=10000; $i<=99999; $i++ ) {
                                $hash = $this->salter($i);
                                if ($hash == $row['password']) {
                                    $resultArr[] = $row['user_id'];
                                }                        
                            }
                        }
                    }
                }
                /* If the selected option is Medium1, it executes the following piece of code.
                Lists the user IDs who have just used 3 Uppercase characters and 1 number as their password i.e. ABC1 */
                else if ($selOption === 'Medium1') {
                    $letters = range('A', 'Z');
                    $numbers = range('0', '9');
                    //array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'));
                    $cnt = count($letters);
                    $numcnt = count($numbers);
                    $strings = '';
                    $result = $this->db->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            /* NOTE: Since the password involves characters and number in the password, loop through the alphabets and the number array
                            and hash the formed string and match with the hashed password stored in the database*/
                            for ( $first = 0; $first < $cnt; $first++ ) {
                               for ( $second = 0; $second < $cnt; $second++ ) {
                                   for ( $third= 0; $third< $cnt; $third++ ) {
                                       for ( $fourth= 0; $fourth < $numcnt; $fourth++ ) {
                                       
                                          $strings = $letters[$first] . $letters[$second] . $letters[$third] . $numbers[$fourth];
                                          $hash = $this->salter($strings);
                                          if ($hash == $row['password']) {
                                              $resultArr[] = $row['user_id'];
                                          }                        
                                       }
                                   }
                               }
                            }
                        }
                    }
                }
                /* If the selected option is Medium2, it executes the following piece of code.
                Lists the user IDs who just have lowercase dictionary words (Max 6 chars) as their passwords i.e. london */
                else if ($selOption === 'Medium2') {
                    /* Create a file and store all the words of 6, 5, 4, 3, 2, 1 characters in the file.*/
                    $wordfile = 'data/words.txt';
                    if (file_exists($wordfile)) {
                        $filecontent = file_get_contents($wordfile);
                        if (!empty($filecontent)) {                    
                            $arraywords = explode(" ", $filecontent);
                            $result = $this->db->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    /* NOTE: Since the password involves characters in the password, loop through the words in the word file
                                    and hash the words and match with the hashed password stored in the database*/
                                    
                                    foreach ($arraywords as $word) {
                                        $hash = $this->salter($word);
                                        if ($hash == $row['password']) {
                                            $resultArr[] = $row['user_id'];
                                        }                        
                                    }
                                }
                            }
                        }
                    }
                }
                /* If the selected option is Hard, it executes the following piece of code.
                Lists the user IDs who have used a 6 character passwords using a mix of Upper, Lowercase and numbers i.e AbC12z */
                else if ($selOption === 'Hard') {
                    $uppercaseletters = range('A', 'Z');
                    $lowercaseletters = range('a', 'z');
                    $numbers = range('0', '9');
                    $cnt = count($uppercaseletters);
                    $numcnt = count($numbers);
                    $strings = '';
                    $result = $this->db->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            /* NOTE: Since the password involves characters and number in the password, loop through the alphabets and the numbers array
                            and hash the formed string and match with the hashed password stored in the database*/
                            for ( $first = 0; $first < $cnt; $first++ ) {
                                for ( $second = 0; $second < $cnt; $second++ ) {
                                    for ( $third= 0; $third < $cnt; $third++ ) {
                                        for ( $fourth = 0; $fourth < $cnt; $fourth++ ) {
                                            for ( $fifth= 0; $fifth < $numcnt; $fifth++ ) {
                                                for ( $sixth= 0; $sixth < $numcnt; $sixth++ ) {
                                                    $strings = $uppercaseletters[$first] . $uppercaseletters[$second] . $lowercaseletters[$third] .  $lowercaseletters[$fourth] . $numbers[$fifth] . $numbers[$sixth];
                                                    $stringArr = $this->permute($strings);

                                                    foreach ($stringArr as $str) {
                                                        $hash = $this->salter($str);
                                                        if ($hash == $row['password']) {
                                                            $resultArr[] = $row['user_id'];
                                                        }
                                                    }
                                                }
                                             }
                                         }                        
                                     }
                                 }
                            }
                        }
                    }
                }
                else {
                
                    $resultArr['error'] = 'Invalid input';
                }
    
                return json_encode($resultArr);
            }

        }
    }

    /**
    * validateData function.
    * Valdates the data to be inserted
    * @return $data - validated data
    */
    private function validateData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        
        return $data;    
    }
    
    /**
    * permute function.
    * This function generated the array of all permutations of the input string.
    * @return $str array of all permutations
    */
    private function permute($str)
    {
        if (strlen($str) < 2) {
            return array($str);
        }
    
        $permutations = array();
        $tail = substr($str, 1);
        foreach ($this->permute($tail) as $permutation) {
            $length = strlen($permutation);
    
            for ($i = 0; $i <= $length; $i++) {
                $permutations[] = substr($permutation, 0, $i) . $str[0] . substr($permutation, $i);
            }
        }
    
        return $permutations;
    }

    /**
    * salter function.
    * This function hashes the string using md5 function
    * @return md5 hashed password
    */
    private function salter($string)
    {
    	$salt = self::SALT;

        return md5($string . $salt);
    }
}
?>