<?php
include '../../templates/header.php';
include_once '../../config/Database.php';
include_once '../../models/Project.php';
include_once '../../models/Group.php';
include_once '../../models/StudentsList.php';
include_once '../../models/Student.php';
include_once '../../Utilty/Utility.php';


// Instantiate DB and connect
$database = new Database();
$db = $database->connect();
// Connecting to projects and groups and student tables
$project = new Project($db);
$groups = new Group($db);
$student = new Student($db);
$student_list = new StudentsList($db);
// Initiate error messages values
$error_messages['user-add-error'] = '';
$error_messages['group-full-error'] = '';

// Set the current project title from GET request
$project->title = isset($_GET['title']) ?$_GET['title'] : die();

// Set project title in groups class, so that we could filter groups by project title
$groups->project_name = $project->title;

// Set project title in student class, so that we could find all the students in the selected project
$student->project_title = $project->title;

// If we get POST request with delete, we deleting the student
if(isset($_POST['delete'])) {
    $student->id = $_POST['id_to_delete'];
    $student->deleteStudent();
    header('Location: get_single.php?title=' .$project->title);
}


// Get project
$project->get_single();

// Get all the groups in the project
$all_groups = $groups->read();
$num = $all_groups->rowCount();
if ($num > 0) {
    $group_arr = array();

    while($row = $all_groups->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $groups = array('id' => $id, 'group_name' => $group_name);
        // push to data
        array_push($group_arr, $groups);
    }
}

// Get the students that are in the project
$project_students = $student->read();
$student_arr = [];
while ($row = $project_students->fetch()) {
    extract($row);
    array_push($student_arr, array('project_name' => $project_name, 'full_name' => $full_name, 'student_id' => $student_id, 'id' => $id, 'group_name' => $group_name, 'group_id' => $group_id));
}
// Get all the students in the students table
$list = $student_list->read();
$list_arr = [];
while ($row = $list->fetch()) {
    extract($row);
    array_push($list_arr, array('id' => $id, 'full_name' => $full_name));
}

// Add the student to the project
if(isset($_POST['students'])) {
    // Get student ID by his name
    $student_list->getId($list_arr, $_POST['students']);
    $student->student_id = $student_list->student_id;

    // Check if student is already in the project
    if(!$student->checkIfExists($student_arr)) {
    $student->addStudent();
    header('Location: get_single.php?title=' .$project->title);
    }
    else {
        $error_messages['user-add-error'] = 'This student is already in the project';
    }
   
}
// If we get POST request with students, we add the student

if(isset($_POST['post_asign'])) {
    $student->group_id = $_POST['group_to_asign'];
    // Get  ID by project name and student name
    $student->getID($student_arr, $project->title, $_POST['asign']);
    $grp_status =Utility::isGroupFull($student_arr,$student->group_id,$project->students_per_grp);
    // Assing student
    if(!$grp_status) {
        $student->asignStudent();
        header('Location: get_single.php?title=' .$project->title);
    }
    else {
        $error_messages['group-full-error'] = 'Group is full';
    }
    
    
}
?>


<div class="container">

    <h4>Project: <?php echo $project->title ?></h4>
    <h4>Number of groups:  <?php echo $project->max_group_num ?></h4>
    <h4>Students per group:  <?php echo $project->students_per_grp ?></h4>

<!-- Students on the project -->
    <div class="card text-center students-card">
        <div class="card-header">
            Students
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm first-row">
                    Student
                </div>
                <div class="col-sm first-row">
                    Group
                </div>
                <div class="col-sm first-row">
                    Action
                </div>
            </div>

            <?php foreach($student_arr as $student) :?>
            <div class="row">
                <div class="col-sm">
                    <?php echo $student['full_name'] ?>
                </div>
                <div class="col-sm">
                <?php echo $student['group_name'] ?>
                </div>
                <div class="col-sm">
                    <!-- Form to delete student -->
                    <form class="delete-form" method="POST" action="get_single.php?title=<?php echo $project->title?>" >
                        <input type="hidden" name="id_to_delete" value="<?php echo $student['id']?>">
                        <input type="submit" name="delete" value="delete">
                    </form>
                </div>
            </div>
            <?php endforeach ;?>
        </div>
    </div>

    <!-- Form to add student -->
    <form action="get_single.php?title=<?php echo $project->title?>" method="POST" class="add-form" autocomplete="off">
        <!-- List all the students -->
        <label for="students">Choose a student</label>
        <input list = "all-students" name="students" id="students" required> 
            <datalist id="all-students">
                <?php foreach($list_arr as $list_item) : ?>
                <option value="<?php echo $list_item['full_name']?>">
                <?php endforeach;?>
            </datalist>
            
        <input type="submit" name="add" value="Add new student">
        </form>
        <div class="error">
            <?php echo  $error_messages['user-add-error']?>
        </div>
<!-- Groups on the project -->
    <div class="groups">
        <?php foreach ($group_arr as $group) : ?>
            <form method="POST" action="get_single.php?title=<?php echo $project->title?>" autocomplete="off">
                <input type="hidden" name="group_to_asign" value="<?php echo $group['id']?>">
                        <div class="card text-center groups-card">
                            <div class="card-header">
                                <?php echo ($group['group_name']) ;  ?>
                            </div>
                            <div class="card-body">
                                <!-- Students in the group -->
                                <?php foreach($student_arr as $student) :?>
                                    <?php if ($group['id'] == $student['group_id']) { ?>
                                    <div class="row">
                                        <div class="col-sm">
                                        <?php echo $student['full_name'] ?>
                                        </div>
                                    </div>
                                    <?php } ?>  
                                <?php endforeach ;?>
                            </div>
                    </div>
                    <input list = "asign-student" name="asign" id="asign" required> 
                    <datalist id="asign-student">
                        <?php foreach($student_arr as $student) : ?>
                            <?php if($student['group_id'] == NULL) : ?>
                        <option value="<?php echo $student['full_name']; endif;?>">
                        <?php endforeach ;?>
                    </datalist>
                <input type="submit" name="post_asign" value="Asign Student">
            </form>
    <!-- Check to see if there was an eror -->
        <div class="error">
            <?php if( isset($_POST['group_to_asign']) && ($_POST['group_to_asign']  == $group['id'])) echo  $error_messages['group-full-error']?>
        </div>
       <?php endforeach ;?>
       
    </div>
    
    

</div>
<?php
include '../../templates/footer.php';