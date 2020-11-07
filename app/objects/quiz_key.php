<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Quiz_key{
        
        // database connection and table name
        private $conn;
        private $table_name = "quiz_key";
     
        // object properties
        public $id;
        public $topic_id;
        public $variant;
        public $version;
        public $updated_at;
        public $author_id;
        public $question_part;
        public $user_id;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
       

        function list($topic_id = 0, $question_part = 1) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE deleted = 0 AND topic_id = ?  ORDER BY version,variant ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $topic_id, PDO::PARAM_INT);
            // execute query
            $stmt->execute();
            
            $data_arr = array();
            $data_arr["quiz_keys"] = array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                //extract($row);
                if($question_part==1) {
                    $data_item = array(
                        "id" => $row['id'],
                        "version" => $row['version'],
                        "variant" => $row['variant'],
                        "q1" => $row['q1'], "q1a" => $row['q1a'],
                        "q2" => $row['q2'], "q2a" => $row['q2a'],
                        "q3" => $row['q3'], "q3a" => $row['q3a'],
                        "q4" => $row['q4'], "q4a" => $row['q4a'],
                        "q5" => $row['q5'], "q5a" => $row['q5a'],
                        "q6" => $row['q6'], "q6a" => $row['q6a'],
                        "q7" => $row['q7'], "q7a" => $row['q7a'],
                        "q8" => $row['q8'], "q8a" => $row['q8a'],
                        "q9" => $row['q9'], "q9a" => $row['q9a'],
                        "q10" => $row['q10'], "q10a" => $row['q10a'],
                        "user_id" => $row['user_id'],
                        "updated_at" => date("H:i d/m/Y" ,strtotime($row['updated_at'])),
                    );
                } 
                
                if($question_part==2) {
                    $data_item = array(
                        "id" => $row['id'],
                        "version" => $row['version'],
                        "variant" => $row['variant'],
                        "q1" => $row['q11'], "q1a" => $row['q11a'],
                        "q2" => $row['q12'], "q2a" => $row['q12a'],
                        "q3" => $row['q13'], "q3a" => $row['q13a'],
                        "q4" => $row['q14'], "q4a" => $row['q14a'],
                        "q5" => $row['q15'], "q5a" => $row['q15a'],
                        "q6" => $row['q16'], "q6a" => $row['q16a'],
                        "q7" => $row['q17'], "q7a" => $row['q17a'],
                        "q8" => $row['q18'], "q8a" => $row['q18a'],
                        "q9" => $row['q19'], "q9a" => $row['q19a'],
                        "q10" => $row['q20'], "q10a" => $row['q20a'],
                        "user_id" => $row['user_id'],
                        "updated_at" => date("H:i d/m/Y" ,strtotime($row['updated_at'])),
                    );
                }
               
                array_push($data_arr["quiz_keys"], $data_item);
                    
            }

            return json_encode($data_arr);
        }

        function show($faculty,$semestr, $topic,$variant,$version) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE `faculty`=".$faculty." AND `topic`=".$topic." AND `semestr`=".$semestr." AND `version`=".$version." AND `variant`=".$variant;
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
    
            return $stmt;
            }

        
          function generate($topic_id = 0, $version_id = 0, $variants_count = 4) {
            $quiz_ids = array();
            $question_number = array();
            $answer_number = array();
            $question_id = array();
            $answer_id = array();
            $temp_ids = 0;
            $tuples = array();
            $version_id = (int) $version_id + 1;


            $query_extra = "SELECT quiz_id FROM quiz_topics WHERE topic_id = ".(int)$topic_id;
            $stmt_extra = $this->conn->prepare($query_extra);
            $stmt_extra->execute();
            if($stmt_extra->rowcount()>0) {
                while($row_extra = $stmt_extra->fetch(PDO::FETCH_ASSOC)){ 
                    $quiz_ids[] = $row_extra['quiz_id'];
                }
            
           
           
            for ($variant = 1;$variant<=$variants_count;$variant++) {
                
                $question_id = array();
                $answer_id = array();

                shuffle($quiz_ids);
                
                $x = 0;
                
                if(count($quiz_ids)<=20) {
                    $quiz_count = count($quiz_ids);
                    
                    while ($x<$quiz_count) {
                        $answer_id[] = rand(1, 4);
                        $question_id[] = $quiz_ids[$x];
                        $x++;
                    }
                    
                    while ($x<20) {
                        $answer_id[] = 0;
                        $question_id[] = 0;
                        $x++;
                    }

                } else {
                    $quiz_count = 20;
                    
                    while ($x<$quiz_count) {
                        $answer_id[] = rand(1, 4);
                        $question_id[] = $quiz_ids[$x];
                        $x++;
                    }
                }

                

                $tuples[] = "(:topic_id, :version, ".$variant.", :user_id, ".implode(", ",$question_id).", ".implode(", ",$answer_id).")";

                
   
                         
                    }
                    
                    $x = 1;
                    while ($x<=20) {
                        $question_number[]= "q".$x;
                        $answer_number[]= "q".$x."a";
                        $x++;
                    }


                    $sql = "INSERT INTO " . $this->table_name . " (topic_id, version, variant, user_id, ".implode(", ",$question_number).", ".implode(", ",$answer_number).") VALUES ".implode(", ",$tuples);


                $stmt =  $this->conn->prepare($sql);
                
                $stmt->bindParam(':topic_id', $topic_id);
                $stmt->bindParam(':version', $version_id);
                $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);

                if($stmt->execute()) return true; 
                //else var_dump($stmt->errorInfo());
                
            }
                
                return false;




          }

          function print($topic_id = 0, $version_id = 0, $questions_count = 1) {
            
            $filename = "blank.xlsx";

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
            
            $styleArray = [
                'borders' => [
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $stmt_topic = $this->conn->query("SELECT t_number, t_name, c_short_name FROM topics LEFT JOIN courses ON courses.id = topics.course_id WHERE topics.id = ". (int) $topic_id);
            $row_topic = $stmt_topic->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT * FROM " . $this->table_name . " WHERE topic_id = ? AND version = ? ORDER BY variant ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $topic_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $version_id, PDO::PARAM_INT);
            
            // execute query
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 

                $variant = $row['variant'];

                $spreadsheet->createSheet();
                $sheet = $spreadsheet->getSheet($variant);
                
                $sheet->setTitle('Вариант '.$variant);

                $sheet->setCellValue('A1', 'Тема №'.$row_topic['t_number'].': '.$row_topic['t_name']);
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->mergeCells('A1:E2');
                
                $sheet->setCellValue('A3', 'Вариант: '.$variant.' Версия: '.$row['version']);
                $sheet->getStyle('A3')->getFont()->setBold(true);
                $sheet->mergeCells('A3:E3');

                $num_offset = 5;
                $x = 0;
                while ($x++<$questions_count*5) {
                    
                    // Question in 1 column
                    $sheet->setCellValue('A'.$num_offset, ''.$x.'.');
                    $sheet->getStyle('A'.$num_offset)->getFont()->setBold(true);
                    $sheet->setCellValue('A'.($num_offset + 1), '1)');
                    $sheet->setCellValue('A'.($num_offset + 2), '2)');
                    $sheet->setCellValue('A'.($num_offset + 3), '3)');
                    $sheet->setCellValue('A'.($num_offset + 4), '4)');

                if($row['q'.$x]!=0) {
                    $stmt_question1 = $this->conn->query("SELECT question, good_answer, bad1_answer, bad2_answer, bad3_answer FROM quiz WHERE id = ". $row['q'.$x]);
                    $row_question1 = $stmt_question1->fetch(PDO::FETCH_ASSOC);


                    switch($row['q'.$x.'a']){
                        case 1:
                            $answer1 = $row_question1['good_answer'];
                            $answer2 = $row_question1['bad1_answer'];
                            $answer3 = $row_question1['bad2_answer'];
                            $answer4 = $row_question1['bad3_answer'];
                            break;
                        case 2:
                            $answer2 = $row_question1['good_answer'];
                            $answer1 = $row_question1['bad1_answer'];
                            $answer3 = $row_question1['bad2_answer'];
                            $answer4 = $row_question1['bad3_answer'];
                            break;
                        case 3:
                            $answer3 = $row_question1['good_answer'];
                            $answer2 = $row_question1['bad1_answer'];
                            $answer1 = $row_question1['bad2_answer'];
                            $answer4 = $row_question1['bad3_answer'];
                        break;
                        case 4:
                            $answer4 = $row_question1['good_answer'];
                            $answer2 = $row_question1['bad1_answer'];
                            $answer3 = $row_question1['bad2_answer'];
                            $answer1 = $row_question1['bad3_answer'];
                        break;
                    }

                    $sheet->setCellValue('B'.$num_offset, ' '.$row_question1['question']);
                    $sheet->getStyle('B'.$num_offset)->getFont()->setBold(true);
                    $sheet->setCellValue('B'.($num_offset + 1), $answer1);
                    $sheet->setCellValue('B'.($num_offset + 2), $answer2);
                    $sheet->setCellValue('B'.($num_offset + 3), $answer3);
                    $sheet->setCellValue('B'.($num_offset + 4), $answer4);

                }
                    
                if($row['q'.($x+5)]!=0) {
                     // Question in 2 column
                    $sheet->setCellValue('D'.$num_offset, ''.($x+5).'.');
                    $sheet->getStyle('D'.$num_offset)->getFont()->setBold(true);
                    $sheet->setCellValue('D'.($num_offset + 1), '1)');
                    $sheet->setCellValue('D'.($num_offset + 2), '2)');
                    $sheet->setCellValue('D'.($num_offset + 3), '3)');
                    $sheet->setCellValue('D'.($num_offset + 4), '4)');

                   
                        $stmt_question2 = $this->conn->query("SELECT question, good_answer, bad1_answer, bad2_answer, bad3_answer FROM quiz WHERE id = ". $row['q'.($x+5)]);
                        $row_question2 = $stmt_question2->fetch(PDO::FETCH_ASSOC);
                    
                    


                    switch($row['q'.($x+5).'a']){
                        case 0:
                            $answer1 = '';
                            $answer2 = '';
                            $answer3 = '';
                            $answer4 = '';
                            break;
                        case 1:
                            $answer1 = $row_question2['good_answer'];
                            $answer2 = $row_question2['bad1_answer'];
                            $answer3 = $row_question2['bad2_answer'];
                            $answer4 = $row_question2['bad3_answer'];
                            break;
                        case 2:
                            $answer2 = $row_question2['good_answer'];
                            $answer1 = $row_question2['bad1_answer'];
                            $answer3 = $row_question2['bad2_answer'];
                            $answer4 = $row_question2['bad3_answer'];
                            break;
                        case 3:
                            $answer3 = $row_question2['good_answer'];
                            $answer2 = $row_question2['bad1_answer'];
                            $answer1 = $row_question2['bad2_answer'];
                            $answer4 = $row_question2['bad3_answer'];
                        break;
                        case 4:
                            $answer4 = $row_question2['good_answer'];
                            $answer2 = $row_question2['bad1_answer'];
                            $answer3 = $row_question2['bad2_answer'];
                            $answer1 = $row_question2['bad3_answer'];
                        break;
                    }

                    $sheet->setCellValue('E'.$num_offset, ' '.$row_question2['question']);
                    $sheet->getStyle('E'.$num_offset)->getFont()->setBold(true);
                    $sheet->setCellValue('E'.($num_offset + 1), $answer1);
                    $sheet->setCellValue('E'.($num_offset + 2), $answer2);
                    $sheet->setCellValue('E'.($num_offset + 3), $answer3);
                    $sheet->setCellValue('E'.($num_offset + 4), $answer4);    
                
                }
                    $num_offset += 6;
                
                }

                $sheet->getHeaderFooter()->setOddHeader('&IЗапишите на дополнительном бланке для ответов выбранные вами варианты ответов цифрами! Только один правильный ответ');

                $sheet->getHeaderFooter()->setOddFooter('&BЗапрещается делать пометки и отмечать ответы на бланке теста! Не фотографировать!');

                $sheet->getStyle('A1:E33')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A1:A33')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D1:D33')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setWidth(55);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setWidth(65);

                $sheet->getStyle('A1:E33')->getAlignment()->setWrapText(true);

                $sheet->getPageSetup()->setPrintArea('A1:E33');
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $variant++;
            }
            
            //$spreadsheet->removeSheetByIndex(0);
            include('parts/quiz_blank.php');

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filename);
                $content = file_get_contents($filename);
            } catch(Exception $e) {
                exit($e->getMessage());
            }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename='.$filename);
            
            
            unlink($filename);
            exit($content);
          }

}        
?>