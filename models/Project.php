<?php 
class Project {
    private $connection;
    private $table = 'project';
    public $title;
    public $max_group_num;
    public $students_per_grp;
    public $group_name;



    // constructor with DB

    public function __construct($db)
    {
        $this->connection =$db;
    }

    // Get projects

    public function read() {
        // Create query
        $query = 'SELECT 
        project.title
        FROM ' . $this->table;

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
        }

    // Create project

    public function createProject () {

        // Create query

        $query = 'INSERT INTO ' . $this->table .' 
        SET
            title = :title,
            max_group_num = :max_group_num,
            students_per_grp = :students_per_grp';
        // Prepare statement

        $stmt = $this->connection->prepare($query);

        // Cleaning data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->max_group_num = htmlspecialchars(strip_tags($this->max_group_num));
        $this->students_per_grp = htmlspecialchars(strip_tags($this->students_per_grp));
        
        // Checking for positive numbers

        if($this->max_group_num > 0 && $this->students_per_grp > 0) {
            // Bind data
            $stmt->bindParam('title', $this->title);
            $stmt->bindParam('max_group_num', $this->max_group_num);
            $stmt->bindParam('students_per_grp', $this->students_per_grp);
        }
        else {
            printf("Only numbers above 0"); die();
        }
        
        if($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        // Print error if its false
        return false;
    }

    // Get single Project

    public function get_single () {
        // Create query
        $query = 'SELECT 
        project.title,
        students_per_grp,
        max_group_num
        FROM ' . $this->table . '
        WHERE project.title = ? 
        ';

        $stmt = $this->connection->prepare($query);
        // Bind ID
        $stmt->bindParam(1, $this->title);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this -> title = $row['title'];
        $this -> max_group_num = $row['max_group_num'];
        $this -> students_per_grp = $row['students_per_grp'];
    }

}
?>