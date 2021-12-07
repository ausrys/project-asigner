<?php
// Headers

include_once '../../config/Database.php';
include_once '../../models/Group.php';
include_once '../../models/Project.php';
include '../../templates/header.php';
$message = '';


?>


    <section>
        <div class="project-create">
        <form action="create.php" method="POST">
            <label>Enter project Name</label>
            <input type="text" name = 'title'>
            <label>Enter maximum number of groups</label>
            <input type="number" name = 'max_group_num' min="1">
            <label>Enter maximum number of stundents per group</label>
            <input type="number" name = 'students_per_grp' min = "1">
            <input type="submit" name= 'submit' value="submit">
        </form>

        </div>
        <a href="http://localhost/project_asigner/api/project/read.php">All projects</a>
    </section>

<?php
if(isset($_POST['submit'])) {
    // Instantiate DB and connect
$database = new Database();
$db = $database->connect();

$project = new Project($db);
// Get posted data
$group = new Group($db);
// $data = json_decode(file_get_contents("php://input"));
$project->title = $_POST['title'];
$project->max_group_num = $_POST['max_group_num'];
$project->students_per_grp = $_POST['students_per_grp'];

// Create project and groups

if($project->createProject()) {
    // If projects created, create groups, use for loop
    for ($i=1; $i <= $_POST['max_group_num']; $i++) { 
        $group->group_name = "Group #" . $i;
        $group->project_name = $_POST['title'];
        $group->create_groups();

        }
    // Redirect if everything is OK
    header('Location: read.php');
}
else {
    echo $message = 'Project was not created';
}
}
