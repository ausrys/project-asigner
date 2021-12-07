<?php 
class Student {
    private $connection;
    private $table = 'student';
    public $project_title;
    public $id;
    public $project_name;
    public $student_id;
    public $group_id;


    public function __construct($db)
    {
        $this->connection = $db;
    }


    // get the students of the project


    public function read() {
        // Create query
        $query = 'SELECT 
        student.id,
        student.project_name,
        student.student_id,
        students.full_name,
        project_group.group_name,
        student.group_id
        FROM ' . $this->table .'
        INNER JOIN project
        ON project.title = student.project_name
        
        INNER JOIN students on students.id = student.student_id
        LEFT JOIN project_group on project_group.id = student.group_id
        WHERE project.title = ?
        ';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->project_title);
        $stmt->execute();
        return $stmt;
    }

    // Deleting a student from the project
    public function deleteStudent() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . '
        WHERE id = :id';
        // Prepare statement
        $stmt = $this->connection->prepare($query);
        // Clear data
        $this->id = htmlspecialchars(strip_tags($this->id));
        // Bind parameter
        $stmt->bindParam('id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        // Print error if its false
        return false;
    }

    // Add student to the student to the project

    public function addStudent() {
        // Create query
        $query = 'INSERT INTO ' . $this->table .' 
        SET
            project_name = :project_title,
            student_id = :student_id';

        $stmt = $this->connection->prepare($query);
        // Cleaning data
        $this->project_title = htmlspecialchars(strip_tags($this->project_title));
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $stmt->bindParam('project_title', $this->project_title);
        $stmt->bindParam('student_id', $this->student_id);
        if($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        // Print error if its false
        return false;
    }

    // Update student table when assigning a student to the group

    public function asignStudent() {
        // Create query
        $query = '
            UPDATE ' . $this->table . '
            SET
            group_id = :group_id
            WHERE id = :id 
        ';

        $stmt = $this->connection->prepare($query);
         // Cleaning data
         $this->group_id = htmlspecialchars(strip_tags($this->group_id));
         $this->id = htmlspecialchars(strip_tags($this->id));
         $stmt->bindParam('group_id', $this->group_id);
         $stmt->bindParam('id', $this->id);
         if($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        // Print error if its false
        return false;
    }

    // Check if student is already in the project

    public function checkIfExists($arr) {
        foreach($arr as $student) {
            if(in_array($this->student_id ,$student)) {
                return true;
                break;
            }
        }
    }

    // get ID to update

    public function getID($arr, $projName, $student_name) {
        foreach($arr as $item) {
            if(($item['project_name'] == $projName) && ($item['full_name'] == $student_name)) {
                $this->id = $item['id'];
                break;
            }
        }
    }

}