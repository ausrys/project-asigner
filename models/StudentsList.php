<?php

class StudentsList {
    private $connection;
    private $table = 'students';
    public $student_id;

    // constructor with DB

    public function __construct($db)
    {
        $this->connection =$db;
    }


    // Get all the students

    public function read() {
        // Create query
        $query = 'SELECT 
        students.full_name,
        students.id
        FROM ' . $this->table;

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
        }

    // Get student id 
    public function getId ($arr, $full_name) {
        foreach($arr as $item) {
            if($item['full_name'] == $full_name ) {
                $this->student_id = $item['id'];
            }
        }
    }
    
}