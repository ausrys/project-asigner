<?php
include_once '../../config/Database.php';
include_once '../../models/Project.php';
include '../../templates/header.php';

// Instantiate DB and connect
$database = new Database();
$db = $database->connect();

$project = new Project($db);

$result = $project->read();

$num = $result->rowCount();

if ($num > 0) {
    $project_arr = array();
    $project_arr['data'] = array();
?>
<!-- Listing all the projects if they exist -->
    <ul>
    
<?php
    while($row = $result->fetch()) {
        extract($row);
?>
        <li>
            <a href="http://localhost/project_asigner/api/project/get_single.php?title=<?php echo ($title);?>"><?php echo ($title);  }?></a>
        </li>
    </ul>

<?php
}
else {
    // No projects
    header('Location: create.php');
}
?>
