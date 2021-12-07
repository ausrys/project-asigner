<?php
class Group {
    private $connection;
    private $table = 'project_group';

    // Properties
    public $project_name;
    public $group_name;


    public function __construct($db)
    {
        $this->connection =$db;
    }


    // Get groups

    public function read() {
        $query = 'SELECT 
        project_group.group_name,
        project_group.id
        FROM ' . $this->table .'
        WHERE project_group.project_name = ?';
        
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->project_name);
        $stmt->execute();
        return $stmt;
        }

    // Create groups on creating the project

    public function create_groups() {
        $query = 'INSERT INTO ' . $this->table .' 
        SET
            group_name = :group_name,
            project_name = :project_name';
        // Prepare statement

        $stmt = $this->connection->prepare($query);

        // Cleaning data
        $this->group_name = htmlspecialchars(strip_tags($this->group_name));
        $this->project_name = htmlspecialchars(strip_tags($this->project_name));

        // Bind data
        $stmt->bindParam('group_name', $this->group_name);
        $stmt->bindParam('project_name', $this->project_name);

        if($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        // Print error if its false
        return false;
    }




}

